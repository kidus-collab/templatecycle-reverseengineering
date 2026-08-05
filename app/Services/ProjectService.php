<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Project\Project;
use App\Models\Template\ProjectTemplate;
use App\Repositories\Project\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepo
    ) {}

    /**
     * Orchestrates the creation of a project, its details, and copying template tracks/milestones.
     */
    public function createProjectWithTemplate(array $data, ?ProjectTemplate $template): Project
    {
        $tag = $template ? ($template->tracks->first()?->name ?? 'Construction Track') : 'Construction Track';

        // 1. Tell the repository to create the project
        $project = $this->projectRepo->createProject([
            'ref' => $data['ref'],
            'name' => $data['name'],
            'tags' => [$tag],
        ]);

        // 2. Tell the repository to create the details
        $detail = $this->projectRepo->createProjectDetail([
            'applied_track' => $tag,
            'status' => 'Upcoming',
        ], $project->id);

        // 3. Tell the repository to copy templates
        if ($template) {
            foreach ($template->tracks as $track) {
                $phase = $this->projectRepo->createProjectPhase([
                    'name' => $track->name,
                    'status' => 'Upcoming',
                    'progress' => 0,
                    'milestones_count' => $track->milestones->count(),
                    'sub_milestones_count' => 0,
                ], $detail->id);

                foreach ($track->milestones as $milestone) {
                    $this->projectRepo->createMilestoneCheckpoint([
                        'title' => $milestone->title,
                        'done' => false,
                    ], $phase->id, $track->id);
                }
            }
        }

        return $project;
    }
}
<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Project\Project;
use App\Models\Project\ProjectDetail;
use App\Models\Project\ProjectPhase;
use App\Models\Project\MilestoneCheckpoint;
use App\Models\Template\ProjectTemplate;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function getAllProjectsWithDetails(): Collection
    {
        return Project::with(['details', 'phases'])->get();
    }

    public function createProjectRecord(array $data, ?int $templateId, string $tag): Project
    {
        $project = new Project([
            'ref' => $data['ref'],
            'name' => $data['name'],
            'tags' => [$tag],
        ]);
        $project->project_template_id = $templateId;
        $project->save();

        return $project;
    }

    public function createDetailRecord(Project $project, string $tag): ProjectDetail
    {
        $detail = new ProjectDetail([
            'applied_track' => $tag,
            'progress_percent' => 0,
            'status' => 'Upcoming',
        ]);
        $project->details()->save($detail);

        return $detail;
    }

    public function createProjectPhase(array $data, int $detailId): ProjectPhase
    {
        $phase = new ProjectPhase($data);
        $phase->project_detail_id = $detailId;
        $phase->save();

        return $phase;
    }

    public function createMilestoneCheckpoint(array $data, int $phaseId, int $trackId): MilestoneCheckpoint
    {
        $checkpoint = new MilestoneCheckpoint($data);
        $checkpoint->project_phase_id = $phaseId;
        $checkpoint->track_id = $trackId;
        $checkpoint->save();

        return $checkpoint;
    }

    public function loadProjectRelations(Project $project): Project
    {
        return $project->load(['details', 'phases.milestoneCheckpoints']);
    }

    public function updateProjectRecord(Project $project, array $data): Project
    {
        $project->update([
            'name' => $data['name'],
            'ref' => $data['ref'],
        ]);

        if ($project->details && isset($data['status'])) {
            $project->details->update([
                'status' => $data['status'],
            ]);
        }

        return $project;
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
    }
}

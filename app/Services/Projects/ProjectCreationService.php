<?php
declare(strict_types=1);

namespace App\Services\Projects;

use App\Models\Project\Project;
use App\Models\Template\ProjectTemplate;
use App\Repositories\Project\ProjectRepositoryInterface;

class ProjectCreationService
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepo
    ) {}

    /**
     * Orchestrates the creation of a project, its details, and copying template tracks/milestones.
     */
    public function createProjectWithTemplate(array $data, ?ProjectTemplate $template): Project
    {
        $tag = $template ? $template->name : 'No Template';

        // 1. Tell the repository to create the project
        $projectData = [
            'ref' => $data['ref'],
            'name' => $data['name'],
            'tags' => [$tag],
        ];

        if ($template) {
            $projectData['project_template_id'] = $template->id;
        }

        $project = $this->projectRepo->createProject($projectData);

        // 2. Tell the repository to create the details
        $detail = $this->projectRepo->createProjectDetail([
            'applied_track' => $tag,
            'status' => 'Upcoming',
        ], $project->id);

        // 3. Tell the repository to copy templates
        if ($template) {
            $template->load('tracks.milestones');
            
            foreach ($template->tracks as $track) {
                // Get only root milestones (Phases)
                $templatePhases = $track->milestones->whereNull('parent_id');
                
                foreach ($templatePhases as $templatePhase) {
                    // Find milestones under this phase
                    $templateMilestones = $track->milestones->where('parent_id', $templatePhase->id);
                    $milestonesCount = $templateMilestones->count();
                    
                    // Find sub-milestones under those milestones
                    $templateMilestoneIds = $templateMilestones->pluck('id')->toArray();
                    $templateSubMilestones = $track->milestones->whereIn('parent_id', $templateMilestoneIds);
                    $subMilestonesCount = $templateSubMilestones->count();
                    
                    $projectPhase = $this->projectRepo->createProjectPhase([
                        'name' => $templatePhase->title,
                        'status' => 'Upcoming',
                        'progress' => 0,
                        'milestones_count' => $milestonesCount,
                        'sub_milestones_count' => $subMilestonesCount,
                    ], $detail->id);

                    foreach ($templateMilestones as $templateMilestone) {
                        $checkpoint = $this->projectRepo->createMilestoneCheckpoint([
                            'title' => $templateMilestone->title,
                            'parent_id' => null,
                            'done' => false,
                        ], $projectPhase->id, $track->id);
                        
                        $subs = $templateSubMilestones->where('parent_id', $templateMilestone->id);
                        foreach ($subs as $sub) {
                            $this->projectRepo->createMilestoneCheckpoint([
                                'title' => $sub->title,
                                'parent_id' => $checkpoint->id,
                                'done' => false,
                            ], $projectPhase->id, $track->id);
                        }
                    }
                }
            }
        }
        
        return $project;
    }

    public function applyTemplateToProject(ProjectTemplate $template, int $projectId): void
    {
        $project = $this->projectRepo->findById($projectId);

        if ($project->project_template_id) {
            throw new \Exception('This project already has a template applied. Please remove the current template first.');
        }

        $project->update(['project_template_id' => $template->id]);
        
        $template->load('tracks.milestones');

        if ($project->details) {
            $project->details->update(['applied_track' => $template->name]);
            foreach ($template->tracks as $track) {
                // Get only root milestones (Phases)
                $templatePhases = $track->milestones->whereNull('parent_id');
                
                foreach ($templatePhases as $templatePhase) {
                    // Find milestones under this phase
                    $templateMilestones = $track->milestones->where('parent_id', $templatePhase->id);
                    $milestonesCount = $templateMilestones->count();
                    
                    // Find sub-milestones under those milestones
                    $templateMilestoneIds = $templateMilestones->pluck('id')->toArray();
                    $templateSubMilestones = $track->milestones->whereIn('parent_id', $templateMilestoneIds);
                    $subMilestonesCount = $templateSubMilestones->count();
                    
                    $projectPhase = $this->projectRepo->createProjectPhase([
                        'name' => $templatePhase->title,
                        'status' => 'Upcoming',
                        'progress' => 0,
                        'milestones_count' => $milestonesCount,
                        'sub_milestones_count' => $subMilestonesCount,
                    ], $project->details->id);

                    foreach ($templateMilestones as $templateMilestone) {
                        $checkpoint = $this->projectRepo->createMilestoneCheckpoint([
                            'title' => $templateMilestone->title,
                            'parent_id' => null,
                            'done' => false,
                        ], $projectPhase->id, $track->id);
                        
                        $subs = $templateSubMilestones->where('parent_id', $templateMilestone->id);
                        foreach ($subs as $sub) {
                            $this->projectRepo->createMilestoneCheckpoint([
                                'title' => $sub->title,
                                'parent_id' => $checkpoint->id,
                                'done' => false,
                            ], $projectPhase->id, $track->id);
                        }
                    }
                }
            }
        }
    }

    public function removeTemplateFromProject(Project $project): void
    {
        if ($project->details) {
            $phases = \App\Models\Project\ProjectPhase::where('project_detail_id', $project->details->id)->get();
            foreach ($phases as $phase) {
                \App\Models\Project\MilestoneCheckpoint::where('project_phase_id', $phase->id)->delete();
                $phase->delete();
            }

            $project->details->update([
                'applied_track' => 'No Template',
                'progress_percent' => 0,
                'status' => 'Upcoming'
            ]);
        }

        $project->update(['project_template_id' => null]);
    }
}

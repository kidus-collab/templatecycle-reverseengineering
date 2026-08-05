<?php
declare(strict_types=1);

namespace App\Repositories\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectDetail;
use App\Models\Project\ProjectPhase;
use App\Models\Project\MilestoneCheckpoint;
use App\Models\Template\ProjectTemplate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function getAllProjectsWithDetails(): Collection
    {
        return Project::with(['details', 'phases'])->get();
    }


  // Creating a new Project , ProjectDetail, ProjectPhase, and MilestoneCheckpoint records in the database.
    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    public function createProjectDetail(array $data, int $projectId): ProjectDetail
    {
        $data['project_id'] = $projectId;
        return ProjectDetail::create($data);
    }

    public function createProjectPhase(array $data, int $detailId): ProjectPhase
    {
        $data['project_detail_id'] = $detailId;
        return ProjectPhase::create($data);
    }

    public function createMilestoneCheckpoint(array $data, int $phaseId, int $trackId): MilestoneCheckpoint
    {
        $data['project_phase_id'] = $phaseId;
        $data['track_id'] = $trackId;
        return MilestoneCheckpoint::create($data);
    }

      
// get a project by its ID along with its details, phases, and milestone checkpoints.
    public function loadProjectMilestones(Project $project): Project
    {
        return $project->load(['details', 'phases.milestoneCheckpoints']);
    }

// Update a project's name, reference, and status.
    public function updateProjectDetail(Project $project, array $data): Project
    {
        $project->update(Arr::only($data, ['name', 'ref']));

        if ($project->details && Arr::has($data, 'status')) {
            $project->details->update( Arr::only($data, ['status']));
        }

        return $project;
    }

// Delete a project.
    public function deleteProject(Project $project): void
    {
        $project->delete();
    }
}

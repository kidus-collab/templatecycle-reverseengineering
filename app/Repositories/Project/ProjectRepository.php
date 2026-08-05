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

    public function createProject(array $data): Project
    {
        $project = new Project($data);
        $project->save();

        return $project;
    }

    public function createProjectDetail(array $data, int $projectId): ProjectDetail
    {
        $detail = new ProjectDetail($data);
        $detail->project_id = $projectId;
        $detail->save();

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

    public function loadProjectMilestones(Project $project): Project
    {
        return $project->load(['details', 'phases.milestoneCheckpoints']);
    }

    public function updateProjectDetail(Project $project, array $data): Project
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

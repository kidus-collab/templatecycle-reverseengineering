<?php
declare(strict_types=1);

namespace App\Repositories\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectDetail;
use App\Models\Project\ProjectPhase;
use App\Models\Project\MilestoneCheckpoint;
use App\Models\Template\ProjectTemplate;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    public function getAllProjectsWithDetails(): Collection;
    public function createProject(array $data): Project;
    public function createProjectDetail(array $data, int $projectId): ProjectDetail;
    public function createProjectPhase(array $data, int $detailId): ProjectPhase;
    public function createMilestoneCheckpoint(array $data, int $phaseId, int $trackId): MilestoneCheckpoint;
    public function loadProjectMilestones(Project $project): Project;
    public function updateProjectDetail(Project $project, array $data): Project;
    public function deleteProject(Project $project): void;
}

<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Project\Project;
use App\Models\Project\ProjectDetail;
use App\Models\Template\ProjectTemplate;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    public function getAllProjectsWithDetails(): Collection;
    public function createProjectRecord(array $data, ?int $templateId, string $tag): Project;
    public function createDetailRecord(Project $project, string $tag): ProjectDetail;
    public function createProjectPhase(array $data, int $detailId): ProjectPhase;
    public function createMilestoneCheckpoint(array $data, int $phaseId, int $trackId): MilestoneCheckpoint;
    public function loadProjectRelations(Project $project): Project;
    public function updateProjectRecord(Project $project, array $data): Project;
    public function deleteProject(Project $project): void;
}

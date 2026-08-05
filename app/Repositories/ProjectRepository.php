<?php 

declare(strict_types=1);

use App\Models\Project\Project;
use App\Models\Project\ProjectDetail;
use App\Models\Project\ProjectPhase;
use App\Models\Project\MilestoneCheckpoint;

Class ProjectRepository implements ProjectRepositoryInterface
{
    
   public function getAllProjects(): Collection
    {
        // eager load the details and phases relationships to avoid N+1 query problem
       return Project::with(['details', 'phases'])->get();
    }

    public function createProject(array $data): Project
    {
        return Project::create($data);
    }
}
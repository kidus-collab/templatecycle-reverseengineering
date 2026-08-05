<?php 

declare(strict_types=1);

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
     public function getAllProjects(): Collection;
    public function createProject(array $data): Project;
}
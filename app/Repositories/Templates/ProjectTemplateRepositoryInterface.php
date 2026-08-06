<?php
declare(strict_types=1);

namespace App\Repositories\Templates;

use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Models\Template\Milestone;
use Illuminate\Database\Eloquent\Collection;

interface ProjectTemplateRepositoryInterface
{
    public function getAllTemplates(): Collection;
    public function getTemplateById(int $id): ProjectTemplate;
    public function createTemplate(array $data): ProjectTemplate;
    public function updateTemplate(ProjectTemplate $template, array $data): bool;
    public function deleteTemplate(ProjectTemplate $template): bool;

    public function createTrack(ProjectTemplate $template, array $data): Track;
    public function updateTrack(Track $track, array $data): bool;
    public function deleteTrack(Track $track): bool;

    public function createMilestone(Track $track, array $data): Milestone;
    public function updateMilestone(Milestone $milestone, array $data): bool;
    public function deleteMilestone(Milestone $milestone): bool;
}

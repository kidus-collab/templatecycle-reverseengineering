<?php
declare(strict_types=1);

namespace App\Repositories\Templates;

use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Models\Template\Milestone;
use Illuminate\Database\Eloquent\Collection;

class ProjectTemplateRepository implements ProjectTemplateRepositoryInterface
{

// Load all project templates along with their associated tracks and milestones.
    public function getAllTemplates(): Collection
    { 
        /**
         * @deprecated Caching heavily nested Eloquent collections is currently causing serialization errors.
         * return \Illuminate\Support\Facades\Cache::remember('templates.all', now()->addHours(24), function () {
         *     return ProjectTemplate::with(['tracks.milestones.subMilestones'])->get();
         * });
         */
        return ProjectTemplate::with(['tracks.milestones.subMilestones'])->get();
    }

    public function getTemplateById(int $id): ProjectTemplate
    {
        return ProjectTemplate::with(['tracks.milestones.subMilestones'])->findOrFail($id);
    }


  // Template Crud operations
    public function createTemplate(array $data): ProjectTemplate
    {
        return ProjectTemplate::create($data);
    }

    public function updateTemplate(ProjectTemplate $template, array $data): bool
    {
        return $template->update($data);
    }

    public function deleteTemplate(ProjectTemplate $template): bool
    {
        return $template->delete();
    }

    // Tracks Crud operations

    public function createTrack(ProjectTemplate $template, array $data): Track
    {
        return $template->tracks()->create($data);
    }

    public function updateTrack(Track $track, array $data): bool
    {
        // @deprecated Cache has been disabled for templates to prevent serialization errors
        // \Illuminate\Support\Facades\Cache::forget('templates.all'); // Invalidate cache on changes
        return $track->update($data);
    }

    public function deleteTrack(Track $track): bool
    {
        return $track->delete();
    }

 // Milestones Crud operations

    public function createMilestone(Track $track, array $data): Milestone
    {
        return $track->milestones()->create($data);
    }

    public function updateMilestone(Milestone $milestone, array $data): bool
    {
        return $milestone->update($data);
    }

    public function deleteMilestone(Milestone $milestone): bool
    {
        return $milestone->delete();
    }
}

<?php
declare(strict_types=1);

namespace App\Services\Templates;

use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Models\Template\Milestone;
use App\Repositories\Templates\ProjectTemplateRepositoryInterface;

class TemplateManagementService
{
    public function __construct(
        private ProjectTemplateRepositoryInterface $repository
    ) {}

    public function createTemplate(array $data): ProjectTemplate
    {
        $data['tracks_count'] = 0;
        $data['milestones_count'] = 0;
        $data['sub_milestones_count'] = 0;
        $data['is_active'] = true;

        return $this->repository->createTemplate($data);
    }

    public function addTrack(ProjectTemplate $template, array $data): Track
    {
        $track = $this->repository->createTrack($template, $data);
        
        $this->repository->updateTemplate($template, [
            'tracks_count' => $template->tracks_count + 1
        ]);

        return $track;
    }

    public function addMilestone(Track $track, array $data): Milestone
    {
        $milestone = $this->repository->createMilestone($track, $data);

        // Update counts
        $template = $track->projectTemplate;
        if (isset($data['parent_id']) && $data['parent_id'] !== null) {
            $this->repository->updateTemplate($template, [
                'sub_milestones_count' => $template->sub_milestones_count + 1
            ]);
        } else {
            $this->repository->updateTemplate($template, [
                'milestones_count' => $template->milestones_count + 1
            ]);
        }

        return $milestone;
    }

    public function deleteTrack(Track $track): void
    {
        $template = $track->projectTemplate;
        // Decrease counts based on milestones
        $milestoneCount = $track->milestones()->whereNull('parent_id')->count();
        $subMilestoneCount = $track->milestones()->whereNotNull('parent_id')->count();

        $this->repository->deleteTrack($track);

        $this->repository->updateTemplate($template, [
            'tracks_count' => max(0, $template->tracks_count - 1),
            'milestones_count' => max(0, $template->milestones_count - $milestoneCount),
            'sub_milestones_count' => max(0, $template->sub_milestones_count - $subMilestoneCount),
        ]);
    }

    public function deleteMilestone(Milestone $milestone): void
    {
        $track = $milestone->track;
        $template = $track->projectTemplate;
        
        $isSub = $milestone->parent_id !== null;

        // If deleting a parent milestone, we also delete its children (cascade), so we need to count them
        $subCount = 0;
        if (!$isSub) {
            $subCount = $milestone->subMilestones()->count();
        }

        $this->repository->deleteMilestone($milestone);

        if ($isSub) {
            $this->repository->updateTemplate($template, [
                'sub_milestones_count' => max(0, $template->sub_milestones_count - 1)
            ]);
        } else {
            $this->repository->updateTemplate($template, [
                'milestones_count' => max(0, $template->milestones_count - 1),
                'sub_milestones_count' => max(0, $template->sub_milestones_count - $subCount),
            ]);
        }
    }
}

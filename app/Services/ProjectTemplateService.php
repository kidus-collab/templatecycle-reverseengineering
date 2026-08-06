<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Models\Template\Milestone;
use App\Repositories\Templates\ProjectTemplateRepositoryInterface;

class ProjectTemplateService
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

    public function syncTemplate(ProjectTemplate $template, array $tracksData): void
    {
        $existingTrackIds = $template->tracks()->pluck('id')->toArray();
        $incomingTrackIds = [];

        foreach ($tracksData as $trackData) {
            $trackId = $trackData['id'] ?? null;
            
            // Check if it's a temporary ID from the frontend
            if ($trackId && str_starts_with((string)$trackId, 'temp-')) {
                $trackId = null;
            }

            if ($trackId) {
                // Update existing track
                $track = Track::find($trackId);
                if ($track) {
                    $track->update(['name' => $trackData['name']]);
                    $incomingTrackIds[] = $track->id;
                    $this->syncMilestones($track, $trackData['milestones'] ?? []);
                }
            } else {
                // Create new track
                $track = $template->tracks()->create(['name' => $trackData['name']]);
                $incomingTrackIds[] = $track->id;
                $this->syncMilestones($track, $trackData['milestones'] ?? []);
            }
        }

        // Delete tracks that are no longer present
        $tracksToDelete = array_diff($existingTrackIds, $incomingTrackIds);
        if (!empty($tracksToDelete)) {
            Track::whereIn('id', $tracksToDelete)->delete();
        }

        // Update template counts
        $this->updateTemplateCounts($template);
    }

    private function syncMilestones(Track $track, array $milestonesData)
    {
        $existingMilestoneIds = $track->milestones()->pluck('id')->toArray();
        $incomingMilestoneIds = [];
        $tempIdMap = [];

        // First pass: Process top-level milestones (Phases)
        foreach ($milestonesData as $msData) {
            if (empty($msData['parent_id'])) {
                $this->processMilestone($track, $msData, null, $incomingMilestoneIds, $tempIdMap);
            }
        }

        // Second pass: Process sub-milestones
        foreach ($milestonesData as $msData) {
            if (!empty($msData['parent_id'])) {
                $parentId = $msData['parent_id'];
                // Resolve temp parent ID if it was just created
                if (is_string($parentId) && isset($tempIdMap[$parentId])) {
                    $parentId = $tempIdMap[$parentId];
                }
                
                // If the parent ID is still a string starting with temp- (and not mapped)
                // then something went wrong (parent not saved), skip it.
                if (is_string($parentId) && str_starts_with($parentId, 'temp-')) {
                    continue;
                }

                $this->processMilestone($track, $msData, $parentId, $incomingMilestoneIds, $tempIdMap);
            }
        }

        // Delete milestones that are no longer present
        $milestonesToDelete = array_diff($existingMilestoneIds, $incomingMilestoneIds);
        if (!empty($milestonesToDelete)) {
            Milestone::whereIn('id', $milestonesToDelete)->delete();
        }
    }

    private function processMilestone(Track $track, array $msData, $parentId, &$incomingMilestoneIds, &$tempIdMap)
    {
        $msId = $msData['id'] ?? null;
        $isTemp = is_string($msId) && str_starts_with($msId, 'temp-');
        $realId = $isTemp ? null : $msId;

        if ($realId) {
            // Update existing
            $milestone = Milestone::find($realId);
            if ($milestone) {
                $milestone->update([
                    'title' => $msData['title'],
                    'parent_id' => $parentId
                ]);
                $incomingMilestoneIds[] = $milestone->id;
            }
        } else {
            // Create new
            $milestone = $track->milestones()->create([
                'title' => $msData['title'],
                'parent_id' => $parentId
            ]);
            $incomingMilestoneIds[] = $milestone->id;
            
            if ($isTemp) {
                $tempIdMap[$msId] = $milestone->id;
            }
        }
    }

    private function updateTemplateCounts(ProjectTemplate $template)
    {
        $tracksCount = $template->tracks()->count();
        $milestonesCount = 0;
        $subMilestonesCount = 0;

        foreach ($template->tracks as $track) {
            $milestonesCount += $track->milestones()->whereNull('parent_id')->count();
            $subMilestonesCount += $track->milestones()->whereNotNull('parent_id')->count();
        }

        $this->repository->updateTemplate($template, [
            'tracks_count' => $tracksCount,
            'milestones_count' => $milestonesCount,
            'sub_milestones_count' => $subMilestonesCount
        ]);
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
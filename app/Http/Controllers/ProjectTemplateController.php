<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectTemplateRequest;
use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Models\Project\Project;
use App\Models\Project\ProjectPhase;
use App\Models\Template\Milestone;
use App\Repositories\Templates\ProjectTemplateRepositoryInterface;
use App\Services\Templates\TemplateManagementService;
use App\Services\Templates\TemplateSyncService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectTemplateController extends Controller
{
    public function __construct(
        private ProjectTemplateRepositoryInterface $repository,
        private TemplateManagementService $managementService,
        private TemplateSyncService $syncService
    ) {}

    public function index()
    {
        $templates = $this->repository->getAllTemplates();
        $projects = Project::all();
        return view('templates.index', compact('templates', 'projects'));
    }

    public function apply(Request $request, ProjectTemplate $template)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::with('details')->findOrFail($validated['project_id']);
        
        // Update project template link
        $project->project_template_id = $template->id;
        $project->save();

        $template->load('tracks.milestones');

        // Copy template tracks & milestones into project phases & checkpoints
        if ($project->details) {
            foreach ($template->tracks as $track) {
                $phase = new ProjectPhase([
                    'name' => $track->name,
                    'status' => 'Upcoming',
                    'progress' => 0,
                    'milestones_count' => $track->milestones->count(),
                    'sub_milestones_count' => 0,
                ]);
                $phase->project_detail_id = $project->details->id;
                $phase->save();

                foreach ($track->milestones as $milestone) {
                    $checkpoint = new \App\Models\Project\MilestoneCheckpoint([
                        'title' => $milestone->title,
                        'done' => false,
                    ]);
                    $checkpoint->project_phase_id = $phase->id;
                    $checkpoint->track_id = $track->id;
                    $checkpoint->save();
                }
            }
        }

        return redirect()->route('templates')->with('success', "Template '{$template->name}' applied to project '{$project->name}' successfully!");
    }

    public function store(ProjectTemplateRequest $request): JsonResponse
    {
        $template = $this->managementService->createTemplate($request->validated());
        $template = $this->repository->getTemplateById($template->id);
        
        return response()->json(['success' => true, 'template' => $template]);
    }

    public function sync(Request $request, ProjectTemplate $template): JsonResponse
    {
        try {
            $validated = $request->validate([
                'tracks' => 'array|nullable',
                'tracks.*.id' => 'nullable',
                'tracks.*.name' => 'required|string',
                'tracks.*.milestones' => 'array|nullable',
                'tracks.*.milestones.*.id' => 'nullable',
                'tracks.*.milestones.*.title' => 'required|string',
                'tracks.*.milestones.*.parent_id' => 'nullable',
            ]);

            $this->syncService->syncTemplate($template, $validated['tracks'] ?? []);
            
            // Reload and return the updated template
            $template = $this->repository->getTemplateById($template->id);
            return response()->json(['success' => true, 'template' => $template]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SYNC ERROR: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    public function storeTrack(ProjectTemplateRequest $request, ProjectTemplate $template): JsonResponse
    {
        $track = $this->managementService->addTrack($template, $request->validated());
        $track->load('milestones.subMilestones');
        return response()->json(['success' => true, 'track' => $track]);
    }
    
    public function updateTrack(ProjectTemplateRequest $request, Track $track): JsonResponse
    {
        $this->repository->updateTrack($track, $request->validated());
        return response()->json(['success' => true]);
    }

    public function destroyTrack(Track $track): JsonResponse
    {
        $this->managementService->deleteTrack($track);
        return response()->json(['success' => true]);
    }

    public function storeMilestone(ProjectTemplateRequest $request, Track $track): JsonResponse
    {
        $milestone = $this->managementService->addMilestone($track, $request->validated());
        $milestone->load('subMilestones');
        return response()->json(['success' => true, 'milestone' => $milestone]);
    }

    public function updateMilestone(ProjectTemplateRequest $request, Milestone $milestone): JsonResponse
    {
        $this->repository->updateMilestone($milestone, $request->validated());
        return response()->json(['success' => true]);
    }

    public function destroyMilestone(Milestone $milestone): JsonResponse
    {
        $this->managementService->deleteMilestone($milestone);
        return response()->json(['success' => true]);
    }
}

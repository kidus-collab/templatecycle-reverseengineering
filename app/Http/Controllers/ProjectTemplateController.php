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

    public function apply(ProjectTemplateRequest $request, ProjectTemplate $template, \App\Services\Projects\ProjectCreationService $projectCreationService)
    {
        $validated = $request->validated();
        $project = Project::findOrFail($validated['project_id']);
        
        // Update project template link
        $project->project_template_id = $template->id;
        $project->save();

        // Delegate to the specialized service
        $projectCreationService->applyTemplateToProject($template, $project->id);

        return redirect()->route('templates')->with('success', "Template '{$template->name}' applied to project '{$project->name}' successfully!");
    }

    public function store(ProjectTemplateRequest $request): JsonResponse
    {
        $template = $this->managementService->createTemplate($request->validated());
        $template = $this->repository->getTemplateById($template->id);
        
        return response()->json(['success' => true, 'template' => $template]);
    }

    public function update(ProjectTemplateRequest $request, ProjectTemplate $template): JsonResponse
    {
        $template->update($request->validated());
        return response()->json(['success' => true, 'template' => $template]);
    }

    public function destroy(ProjectTemplate $template): JsonResponse
    {
        $template->delete();
        return response()->json(['success' => true]);
    }

    public function sync(ProjectTemplateRequest $request, ProjectTemplate $template): JsonResponse
    {
        $validated = $request->validated();

        $this->syncService->syncTemplate($template, $validated['tracks'] ?? []);
        
        // Reload and return the updated template
        $template = $this->repository->getTemplateById($template->id);
        return response()->json(['success' => true, 'template' => $template]);
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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectTemplateRequest;
use App\Models\Project\Project;
use App\Models\Template\Milestone;
use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Repositories\Templates\ProjectTemplateRepositoryInterface;
use App\Services\Projects\ProjectCreationService;
use App\Services\Templates\TemplateManagementService;
use App\Services\Templates\TemplateSyncService;
use Illuminate\Http\JsonResponse;

class ProjectTemplateController extends Controller
{
    public function __construct(
        private ProjectTemplateRepositoryInterface $repository,
        private TemplateManagementService $managementService,
    ) {}

    public function index()
    {
        $templates = $this->repository->getAllTemplates();
        $projects = Project::all();

        return view('templates.index', compact('templates', 'projects'));
    }

    // Apply a template to a project
    public function apply(ProjectTemplateRequest $request, ProjectTemplate $template, ProjectCreationService $projectCreationService)
    {
        $validated = $request->validated();
        $project = Project::findOrFail($validated['project_id']);

        try {
            $projectCreationService->applyTemplateToProject($template, $project->id);
            return redirect()->route('templates')->with('success', "Template '{$template->name}' applied to project '{$project->name}' successfully!");
        } catch (\Exception $e) {
            return redirect()->route('templates')->with('error', $e->getMessage());
        }
    }

    // Template store , update & delete methods
    public function store(ProjectTemplateRequest $request): JsonResponse
    {
        $template = $this->managementService->createTemplate($request->validated());
        $template = $this->repository->getTemplateById($template->id);

        return response()->json(['success' => true, 'template' => $template]);
    }

    public function update(ProjectTemplateRequest $request, ProjectTemplate $template): JsonResponse
    {
        $this->repository->updateTemplate($template, $request->validated());

        return response()->json(['success' => true, 'template' => $template]);
    }

    public function destroy(ProjectTemplate $template): JsonResponse
    {
        $this->repository->deleteTemplate($template);

        return response()->json(['success' => true]);
    }

    // Template sync : Save the entire template structure (tracks, milestones, sub-milestones) in one go (batch saving)
    public function sync(ProjectTemplateRequest $request, ProjectTemplate $template, TemplateSyncService $syncService): JsonResponse
    {
        $validated = $request->validated();

        $syncService->syncTemplate($template, $validated['tracks'] ?? []);

        $template = $this->repository->getTemplateById($template->id);

        return response()->json(['success' => true, 'template' => $template]);
    }

    // track store , update & delete methods

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

    // Milestone store , update & delete methods
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

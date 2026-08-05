<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Project\ProjectRepositoryInterface;
use App\Repositories\Project\ProjectRepository;
use App\Http\Requests\ProjectRequest;   
use App\Services\ProjectService;
use App\Models\Template\ProjectTemplate;
use App\Models\Project\Project;

class ProjectController extends Controller
{
    private ProjectRepositoryInterface $projectRepository;
    private ProjectService $projectService;

    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        ProjectService $projectService
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectService = $projectService;
    }

    /**
     * Display projects portfolio gallery view.
     */
    public function index()
    {
        $projects = $this->projectRepository->getAllProjectsWithDetails();
        $templates = ProjectTemplate::all();

        return view('projects.index', compact('projects', 'templates'));
    }

    /**
     * Store a newly created project in database.
     */
    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();

        $template = !empty($validated['project_template_id']) 
            ? ProjectTemplate::with('tracks.milestones')->find($validated['project_template_id']) 
            : null;

        $project = $this->projectService->createProjectWithTemplate($validated, $template);

        return redirect()->route('projects.index')->with('success', "Project '{$project->name}' created successfully!");
    }

    /**
     * Display single project detail view with stage template progress milestones.
     */
    public function show(Project $project)
    {
        $project = $this->projectRepository->loadProjectMilestones($project);

        return view('projects.show', compact('project'));
    }

    /**
     * Update specified project in database.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        
        $this->projectRepository->updateProjectDetail($project, $validated);

        return redirect()->back()->with('success', "Project '{$project->name}' updated successfully!");
    }

    /**
     * Remove the specified project from database.
     */
    public function destroy(Project $project)
    {
        $name = $project->name;
        $this->projectRepository->deleteProject($project);

        return redirect()->route('projects.index')->with('success', "Project '{$name}' deleted successfully!");
    }
}

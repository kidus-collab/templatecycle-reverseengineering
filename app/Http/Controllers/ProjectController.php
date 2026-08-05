<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ProjectRepositoryInterface;
use App\Http\Requests\ProjectRequest;   
use App\Services\ProjectService;


use App\Models\Template\ProjectTemplate;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display projects portfolio gallery view.
     */
    public function index()
    {
        $projects = $this->projectRepository->getAllProjects();
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

        $tag = $template ? ($template->tracks->first()?->name ?? 'Construction Track') : 'Construction Track';

        $project = new Project([
            'ref' => $validated['ref'],
            'name' => $validated['name'],
            'tags' => [$tag],
        ]);
        $project->project_template_id = $template?->id;
        $project->save();

        $detail = new ProjectDetail([
            'applied_track' => $tag,
            'progress_percent' => 0,
            'status' => 'Upcoming',
        ]);
        $project->details()->save($detail);

        if ($template) {
            $this->copyTemplateToProject($template, $detail->id);
        }

        return redirect()->route('projects.index')->with('success', "Project '{$project->name}' created successfully!");
    }

    /**
     * Copy tracks and milestones from a template into project phases and checkpoints.
     */
    public function copyTemplateToProject(ProjectTemplate $template, int $detailId): void
    {
        foreach ($template->tracks as $track) {
            $phase = new ProjectPhase([
                'name' => $track->name,
                'status' => 'Upcoming',
                'progress' => 0,
                'milestones_count' => $track->milestones->count(),
                'sub_milestones_count' => 0,
            ]);
            $phase->project_detail_id = $detailId;
            $phase->save();

            foreach ($track->milestones as $milestone) {
                $checkpoint = new MilestoneCheckpoint([
                    'title' => $milestone->title,
                    'done' => false,
                ]);
                $checkpoint->project_phase_id = $phase->id;
                $checkpoint->track_id = $track->id;
                $checkpoint->save();
            }
        }
    }

    /**
     * Display single project detail view with stage template progress milestones.
     */
    public function show(Project $project)
    {
        $project->load(['details', 'phases.milestoneCheckpoints']);

        return view('projects.show', compact('project'));
    }

    /**
     * Update specified project in database.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ref' => 'required|string|max:255|unique:projects,ref,' . $project->id,
            'status' => 'nullable|string|max:255',
        ]);

        $project->update([
            'name' => $validated['name'],
            'ref' => $validated['ref'],
        ]);

        if ($project->details && isset($validated['status'])) {
            $project->details->update([
                'status' => $validated['status'],
            ]);
        }

        return redirect()->back()->with('success', "Project '{$project->name}' updated successfully!");
    }

    /**
     * Remove the specified project from database.
     */
    public function destroy(Project $project)
    {
        $name = $project->name;
        $project->delete();

        return redirect()->route('projects.index')->with('success', "Project '{$name}' deleted successfully!");
    }
}

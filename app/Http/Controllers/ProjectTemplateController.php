<?php

namespace App\Http\Controllers;

use App\Models\Template\ProjectTemplate;
use App\Models\Project\Project;
use App\Models\Project\ProjectPhase;
use App\Models\Project\MilestoneCheckpoint;
use Illuminate\Http\Request;

class ProjectTemplateController extends Controller
{
    /**
     * Display project stage templates list and editor.
     */
    public function index()
    {
        $templates = ProjectTemplate::with('tracks.milestones')->get();
        $projects = Project::all();

        return view('projects.templates', compact('templates', 'projects'));
    }

    /**
     * Apply a stage template to an existing project.
     */
    public function apply(Request $request, ProjectTemplate $template)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::with('details')->findOrFail($validated['project_id']);

        // Update project template link
        $project->update(['project_template_id' => $template->id]);

        $template->load('tracks.milestones');

        // Copy template tracks & milestones into project phases & checkpoints
        if ($project->details) {
            foreach ($template->tracks as $track) {
                $phase = ProjectPhase::create([
                    'project_detail_id' => $project->details->id,
                    'name' => $track->name,
                    'status' => 'Upcoming',
                    'progress' => 0,
                    'milestones_count' => $track->milestones->count(),
                    'sub_milestones_count' => 0,
                ]);

                foreach ($track->milestones as $milestone) {
                    MilestoneCheckpoint::create([
                        'project_phase_id' => $phase->id,
                        'track_id' => $track->id,
                        'title' => $milestone->title,
                        'done' => false,
                    ]);
                }
            }
        }

        return redirect()->route('templates')->with('success', "Template '{$template->name}' applied to project '{$project->name}' successfully!");
    }
}

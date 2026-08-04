<?php

namespace App\Http\Controllers;

use App\Models\Project\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display projects portfolio gallery view.
     */
    public function index()
    {
        $projects = Project::with(['details', 'phases'])->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Display single project detail view with stage template progress milestones.
     */
    public function show($ref)
    {
        $project = Project::with(['details', 'phases.milestoneCheckpoints'])
            ->where('ref', $ref)
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }
}

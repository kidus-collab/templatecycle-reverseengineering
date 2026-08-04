<?php

namespace App\Http\Controllers;

use App\Models\Template\ProjectTemplate;
use Illuminate\Http\Request;

class ProjectTemplateController extends Controller
{
    /**
     * Display project stage templates list and editor.
     */
    public function index()
    {
        $templates = ProjectTemplate::with('tracks.milestones')->get();

        return view('projects.templates', compact('templates'));
    }
}

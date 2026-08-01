<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display projects portfolio gallery view.
     */
    public function index()
    {
        $projects = [
            [
                'ref' => 'MC26-L-G-1-MT1',
                'name' => 'Mesob Tower',
                'tags' => ['Construction Track'],
                'moreTags' => '',
                'milestones' => '0/128',
                'subMilestones' => '0/723',
            ],
            [
                'ref' => 'MC26-L-G1-AT1',
                'name' => 'Atlas tower',
                'tags' => ['Design Permit Track'],
                'moreTags' => '',
                'milestones' => '0/7',
                'subMilestones' => '0/10',
            ],
            [
                'ref' => 'PM-DRAFT-001',
                'name' => 'Aurora Mobility Campus',
                'tags' => ['Construction Track'],
                'moreTags' => '',
                'milestones' => '0/128',
                'subMilestones' => '0/723',
            ],
            [
                'ref' => 'PM-ACTIVE-002',
                'name' => 'Harbor Logistics Hub',
                'tags' => ['Supervision Track'],
                'moreTags' => '',
                'milestones' => '0/128',
                'subMilestones' => '0/723',
            ],
            [
                'ref' => 'PM-ACTIVE-003',
                'name' => 'Abyssinia Financial Center',
                'tags' => ['Construction Track'],
                'moreTags' => '',
                'milestones' => '12/24',
                'subMilestones' => '45/100',
            ],
            [
                'ref' => 'PM-HANDOVER-004',
                'name' => 'Entoto Heights Complex',
                'tags' => ['Handover Track'],
                'moreTags' => '',
                'milestones' => '60/60',
                'subMilestones' => '200/200',
            ],
        ];

        return view('projects.index', compact('projects'));
    }

    /**
     * Display single project detail view.
     */
    public function show($ref)
    {
        $project = [
            'ref' => $ref,
            'name' => 'Project Details (' . $ref . ')',
            'tags' => ['Construction Track'],
            'milestones' => '12/24',
            'subMilestones' => '45/100',
        ];

        return view('projects.show', compact('project'));
    }
}

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
                'milestones' => '54/128',
                'subMilestones' => '310/723',
            ],
            [
                'ref' => 'MC26-L-G1-AT1',
                'name' => 'Atlas tower',
                'tags' => ['Design Permit Track'],
                'moreTags' => '',
                'milestones' => '2/7',
                'subMilestones' => '4/10',
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
                'milestones' => '18/128',
                'subMilestones' => '95/723',
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
     * Display single project detail view with stage template progress milestones.
     */
    public function show($ref)
    {
        $projectsData = [
            'MC26-L-G-1-MT1' => [
                'ref' => 'MC26-L-G-1-MT1',
                'name' => 'Mesob Tower',
                'appliedTrack' => 'Construction Track',
                'progressPercent' => 42,
                'milestones' => '54/128',
                'subMilestones' => '310/723',
                'status' => 'In Progress',
                'phases' => [
                    [
                        'id' => 1,
                        'name' => 'Pre-construction',
                        'status' => 'Completed',
                        'progress' => 100,
                        'milestonesCount' => '16/16',
                        'subMilestonesCount' => '90/90',
                        'checkpoints' => [
                            ['title' => 'Site Survey & Soil Test', 'done' => true],
                            ['title' => 'Zoning Approval & Environmental Permit', 'done' => true],
                            ['title' => 'Architectural Design Sign-off', 'done' => true],
                        ]
                    ],
                    [
                        'id' => 2,
                        'name' => 'Sub structure (B1)',
                        'status' => 'In Progress',
                        'progress' => 65,
                        'milestonesCount' => '16/24',
                        'subMilestonesCount' => '91/140',
                        'checkpoints' => [
                            ['title' => 'Excavation & Shoring Stage', 'done' => true],
                            ['title' => 'Foundation Piling & Concrete Pour', 'done' => true],
                            ['title' => 'Basement Retaining Wall Framing', 'done' => false],
                        ]
                    ],
                    [
                        'id' => 3,
                        'name' => 'Super structure (GF)',
                        'status' => 'Upcoming',
                        'progress' => 0,
                        'milestonesCount' => '0/32',
                        'subMilestonesCount' => '0/180',
                        'checkpoints' => [
                            ['title' => 'Ground Floor Slab Concrete Pour', 'done' => false],
                            ['title' => 'Structural Column Reinforcement', 'done' => false],
                        ]
                    ],
                    [
                        'id' => 4,
                        'name' => 'Interior Construction',
                        'status' => 'Upcoming',
                        'progress' => 0,
                        'milestonesCount' => '0/20',
                        'subMilestonesCount' => '0/110',
                        'checkpoints' => [
                            ['title' => 'Drywall Framing & Insulation', 'done' => false],
                            ['title' => 'MEP Rough-in Installation', 'done' => false],
                        ]
                    ],
                    [
                        'id' => 5,
                        'name' => 'Exterior Finishing Works',
                        'status' => 'Upcoming',
                        'progress' => 0,
                        'milestonesCount' => '0/12',
                        'subMilestonesCount' => '0/80',
                        'checkpoints' => [
                            ['title' => 'Curtain Wall Glass Installation', 'done' => false],
                        ]
                    ],
                    [
                        'id' => 6,
                        'name' => 'Exterior construction',
                        'status' => 'Upcoming',
                        'progress' => 0,
                        'milestonesCount' => '0/10',
                        'subMilestonesCount' => '0/60',
                        'checkpoints' => [
                            ['title' => 'Hardscaping & Paving', 'done' => false],
                        ]
                    ],
                    [
                        'id' => 7,
                        'name' => 'Post Construction',
                        'status' => 'Upcoming',
                        'progress' => 0,
                        'milestonesCount' => '0/8',
                        'subMilestonesCount' => '0/40',
                        'checkpoints' => [
                            ['title' => 'Snag List Clearing & Sign-off', 'done' => false],
                        ]
                    ],
                    [
                        'id' => 8,
                        'name' => 'Cost Tracking',
                        'status' => 'In Progress',
                        'progress' => 50,
                        'milestonesCount' => '3/6',
                        'subMilestonesCount' => '12/23',
                        'checkpoints' => [
                            ['title' => 'Interim Payment Certificate #3', 'done' => true],
                            ['title' => 'Budget Audit Review', 'done' => false],
                        ]
                    ],
                ]
            ]
        ];

        $project = $projectsData[$ref] ?? [
            'ref' => $ref,
            'name' => 'Project ' . $ref,
            'appliedTrack' => 'Construction Track',
            'progressPercent' => 25,
            'milestones' => '12/24',
            'subMilestones' => '45/100',
            'status' => 'In Progress',
            'phases' => [
                [
                    'id' => 1,
                    'name' => 'Pre-construction',
                    'status' => 'Completed',
                    'progress' => 100,
                    'milestonesCount' => '12/12',
                    'subMilestonesCount' => '45/45',
                    'checkpoints' => [
                        ['title' => 'Initial Kickoff & Site Survey', 'done' => true],
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Sub structure (B1)',
                    'status' => 'In Progress',
                    'progress' => 40,
                    'milestonesCount' => '0/12',
                    'subMilestonesCount' => '0/55',
                    'checkpoints' => [
                        ['title' => 'Excavation Phase', 'done' => true],
                        ['title' => 'Piling Works', 'done' => false],
                    ]
                ]
            ]
        ];

        return view('projects.show', compact('project'));
    }
}

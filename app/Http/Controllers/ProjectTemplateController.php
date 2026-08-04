<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectTemplateController extends Controller
{
    /**
     * Display project stage templates list and editor.
     */
    public function index()
    {
        $templates = [
            [
                'id' => 'const-tpl',
                'name' => 'Construction template',
                'description' => 'Construction',
                'tracksCount' => 8,
                'phasesCount' => 63,
                'milestonesCount' => 364,
                'subMilestonesCount' => 0,
                'isActive' => false,
                'tracks' => [
                    ['id' => 'c1', 'name' => 'Civil Works', 'count' => 12],
                    ['id' => 'c2', 'name' => 'Structural', 'count' => 18],
                ],
            ],
            [
                'id' => 'design-tpl',
                'name' => 'Design Template',
                'description' => 'Template used for design projects',
                'tracksCount' => 3,
                'phasesCount' => 22,
                'milestonesCount' => 221,
                'subMilestonesCount' => 0,
                'isActive' => false,
                'tracks' => [
                    ['id' => 'd1', 'name' => 'Architectural', 'count' => 8],
                    ['id' => 'd2', 'name' => 'MEP Engineering', 'count' => 14],
                ],
            ],
            [
                'id' => 'integ-tpl',
                'name' => 'Integrated Delivery Baseline',
                'description' => 'Design-through-handover template for full-spectrum Mers delivery programs.',
                'tracksCount' => 3,
                'phasesCount' => 7,
                'milestonesCount' => 7,
                'subMilestonesCount' => 10,
                'isActive' => false,
                'tracks' => [
                    ['id' => 'i1', 'name' => 'Program Setup', 'count' => 3],
                    ['id' => 'i2', 'name' => 'Handover Track', 'count' => 4],
                ],
            ],
            [
                'id' => 'quick-tpl',
                'name' => 'Quick Template',
                'description' => 'Consists of 3 Tracks : Construction | Design | Supervision',
                'tracksCount' => 3,
                'phasesCount' => 16,
                'milestonesCount' => 128,
                'subMilestonesCount' => 723,
                'isActive' => true,
                'tracks' => [
                    [
                        'id' => 'q-const',
                        'name' => 'Construction',
                        'count' => 8,
                        'phases' => [
                            ['id' => 1, 'name' => 'Pre-construction', 'starred' => false],
                            ['id' => 2, 'name' => 'Sub structure (B1)', 'starred' => false],
                            ['id' => 3, 'name' => 'Super structure (GF)', 'starred' => false],
                            ['id' => 4, 'name' => 'Interior Construction', 'starred' => false],
                            ['id' => 5, 'name' => 'Exterior Finishing Works', 'starred' => false],
                            ['id' => 6, 'name' => 'Exterior construction', 'starred' => false],
                            ['id' => 7, 'name' => 'Post Construction', 'starred' => false],
                            ['id' => 8, 'name' => 'Cost Tracking', 'starred' => false],
                        ],
                    ],
                    [
                        'id' => 'q-design',
                        'name' => 'Design',
                        'count' => 3,
                        'phases' => [
                            ['id' => 9, 'name' => 'Conceptual Sketching', 'starred' => false],
                            ['id' => 10, 'name' => 'Detailed Blueprinting', 'starred' => true],
                            ['id' => 11, 'name' => 'Client Approval Sign-off', 'starred' => false],
                        ],
                    ],
                    [
                        'id' => 'q-supervision',
                        'name' => 'Supervision...',
                        'count' => 5,
                        'phases' => [
                            ['id' => 12, 'name' => 'Site Inspection Stage 1', 'starred' => false],
                            ['id' => 13, 'name' => 'Safety Audit Phase', 'starred' => false],
                            ['id' => 14, 'name' => 'Compliance Review', 'starred' => false],
                            ['id' => 15, 'name' => 'Material Testing Verification', 'starred' => false],
                            ['id' => 16, 'name' => 'Final Commissioning Audit', 'starred' => false],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'sup-rec-tpl',
                'name' => 'Supervision Recovery Sprint',
                'description' => 'Focused supervision and construction template for late-stage recovery programs.',
                'tracksCount' => 2,
                'phasesCount' => 3,
                'milestonesCount' => 3,
                'subMilestonesCount' => 4,
                'isActive' => false,
                'tracks' => [
                    ['id' => 'sr1', 'name' => 'Fast-Track Remediation', 'count' => 2],
                    ['id' => 'sr2', 'name' => 'Sprint Signoff', 'count' => 1],
                ],
            ],
            [
                'id' => 'sup-tpl',
                'name' => 'Supervision Template',
                'description' => 'supervision project template',
                'tracksCount' => 1,
                'phasesCount' => 4,
                'milestonesCount' => 12,
                'subMilestonesCount' => 18,
                'isActive' => false,
                'tracks' => [
                    ['id' => 'st1', 'name' => 'General Supervision', 'count' => 4],
                ],
            ],
        ];

        return view('projects.templates', compact('templates'));
    }
}

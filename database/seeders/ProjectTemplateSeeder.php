<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Models\Template\Milestone;

class ProjectTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templatesData = [
            [
                'id' => 'const-tpl',
                'name' => 'Construction template',
                'description' => 'Construction',
                'tracksCount' => 2,
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
                'tracksCount' => 2,
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
                'tracksCount' => 2,
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
                'milestonesCount' => 128,
                'subMilestonesCount' => 723,
                'isActive' => true,
                'tracks' => [
                    [
                        'id' => 'q-const',
                        'name' => 'Construction',
                        'count' => 8,
                        'phases' => [
                            ['name' => 'Pre-construction'],
                            ['name' => 'Sub structure (B1)'],
                            ['name' => 'Super structure (GF)'],
                            ['name' => 'Interior Construction'],
                            ['name' => 'Exterior Finishing Works'],
                            ['name' => 'Exterior construction'],
                            ['name' => 'Post Construction'],
                            ['name' => 'Cost Tracking'],
                        ],
                    ],
                    [
                        'id' => 'q-design',
                        'name' => 'Design',
                        'count' => 3,
                        'phases' => [
                            ['name' => 'Conceptual Sketching'],
                            ['name' => 'Detailed Blueprinting'],
                            ['name' => 'Client Approval Sign-off'],
                        ],
                    ],
                    [
                        'id' => 'q-supervision',
                        'name' => 'Supervision...',
                        'count' => 5,
                        'phases' => [
                            ['name' => 'Site Inspection Stage 1'],
                            ['name' => 'Safety Audit Phase'],
                            ['name' => 'Compliance Review'],
                            ['name' => 'Material Testing Verification'],
                            ['name' => 'Final Commissioning Audit'],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'sup-rec-tpl',
                'name' => 'Supervision Recovery Sprint',
                'description' => 'Focused supervision and construction template for late-stage recovery programs.',
                'tracksCount' => 2,
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
                'milestonesCount' => 12,
                'subMilestonesCount' => 18,
                'isActive' => false,
                'tracks' => [
                    ['id' => 'st1', 'name' => 'General Supervision', 'count' => 4],
                ],
            ],
        ];

        foreach ($templatesData as $tplData) {
            $template = ProjectTemplate::create([
                'name' => $tplData['name'],
                'description' => $tplData['description'],
                'tracks_count' => $tplData['tracksCount'],
                'milestones_count' => $tplData['milestonesCount'],
                'sub_milestones_count' => $tplData['subMilestonesCount'],
                'is_active' => $tplData['isActive'],
            ]);

            foreach ($tplData['tracks'] as $trackData) {
                $track = Track::create([
                    'project_template_id' => $template->id,
                    'name' => $trackData['name'],
                ]);

                if (isset($trackData['phases'])) {
                    foreach ($trackData['phases'] as $phaseData) {
                        Milestone::create([
                            'track_id' => $track->id,
                            'parent_id' => null,
                            'title' => $phaseData['name'],
                        ]);
                    }
                } else {
                    // Generate some dummy milestones to match count
                    $count = $trackData['count'] ?? 0;
                    for ($i = 1; $i <= $count; $i++) {
                        Milestone::create([
                            'track_id' => $track->id,
                            'parent_id' => null,
                            'title' => 'Dummy Phase ' . $i,
                        ]);
                    }
                }
            }
        }
    }
}

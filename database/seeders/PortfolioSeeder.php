<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Models\Template\Milestone;
use App\Models\Project\Project;
use App\Models\Project\ProjectDetail;
use App\Models\Project\ProjectPhase;
use App\Models\Project\MilestoneCheckpoint;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. FETCH TEMPLATE & TRACK
        $template = ProjectTemplate::where('name', 'Construction template')->first();
        if (!$template) {
            $template = ProjectTemplate::create([
                'name' => 'Construction Template',
                'description' => 'Standard template for construction projects.',
                'tracks_count' => 1,
                'milestones_count' => 8,
                'sub_milestones_count' => 12,
                'is_active' => true,
            ]);
        }

        $track = Track::where('project_template_id', $template->id)->first();
        if (!$track) {
            $track = Track::create([
                'project_template_id' => $template->id,
                'name' => 'Construction Track',
                'phase_name' => 'Construction Phase',
            ]);
        }

        // (We could create blueprint Milestones here too, but we will focus on Project instances for the view to work)

        // 2. CREATE PROJECTS (from original controller array)
        $projectsData = [
            [
                'ref' => 'MC26-L-G-1-MT1',
                'name' => 'Mesob Tower',
                'tags' => ['Construction Track'],
                'moreTags' => '',
                'progressPercent' => 42,
                'status' => 'In Progress',
            ],
            [
                'ref' => 'MC26-L-G1-AT1',
                'name' => 'Atlas tower',
                'tags' => ['Design Permit Track'],
                'moreTags' => '',
                'progressPercent' => 15,
                'status' => 'Upcoming',
            ],
            [
                'ref' => 'PM-DRAFT-001',
                'name' => 'Aurora Mobility Campus',
                'tags' => ['Construction Track'],
                'moreTags' => '',
                'progressPercent' => 0,
                'status' => 'Upcoming',
            ],
            [
                'ref' => 'PM-ACTIVE-002',
                'name' => 'Harbor Logistics Hub',
                'tags' => ['Supervision Track'],
                'moreTags' => '',
                'progressPercent' => 22,
                'status' => 'In Progress',
            ],
            [
                'ref' => 'PM-ACTIVE-003',
                'name' => 'Abyssinia Financial Center',
                'tags' => ['Construction Track'],
                'moreTags' => '',
                'progressPercent' => 45,
                'status' => 'In Progress',
            ],
            [
                'ref' => 'PM-HANDOVER-004',
                'name' => 'Entoto Heights Complex',
                'tags' => ['Handover Track'],
                'moreTags' => '',
                'progressPercent' => 100,
                'status' => 'Completed',
            ],
        ];

        foreach ($projectsData as $pData) {
            $project = Project::create([
                'ref' => $pData['ref'],
                'name' => $pData['name'],
                'tags' => $pData['tags'],
                'more_tags' => $pData['moreTags'],
            ]);

            $detail = ProjectDetail::create([
                'project_id' => $project->id,
                'applied_track' => $pData['tags'][0],
                'status' => $pData['status'],
            ]);

            // ONLY seed phases/checkpoints for Mesob Tower (to match original controller data)
            if ($pData['ref'] === 'MC26-L-G-1-MT1') {
                $this->seedMesobTowerPhases($detail->id, $track->id);
            }
        }
    }

    private function seedMesobTowerPhases($detailId, $trackId)
    {
        $phasesData = [
            [
                'name' => 'Pre-construction',
                'status' => 'Completed',
                'progress' => 100,
                'milestones_count' => 16,
                'sub_milestones_count' => 90,
                'checkpoints' => [
                    ['title' => 'Site Survey & Soil Test', 'done' => true],
                    ['title' => 'Zoning Approval & Environmental Permit', 'done' => true],
                    ['title' => 'Architectural Design Sign-off', 'done' => true],
                ]
            ],
            [
                'name' => 'Sub structure (B1)',
                'status' => 'In Progress',
                'progress' => 65,
                'milestones_count' => 16,
                'sub_milestones_count' => 91,
                'checkpoints' => [
                    ['title' => 'Excavation & Shoring Stage', 'done' => true],
                    ['title' => 'Foundation Piling & Concrete Pour', 'done' => true],
                    ['title' => 'Basement Retaining Wall Framing', 'done' => false],
                ]
            ],
            [
                'name' => 'Super structure (GF)',
                'status' => 'Upcoming',
                'progress' => 0,
                'milestones_count' => 0,
                'sub_milestones_count' => 0,
                'checkpoints' => [
                    ['title' => 'Ground Floor Slab Concrete Pour', 'done' => false],
                    ['title' => 'Structural Column Reinforcement', 'done' => false],
                ]
            ],
            [
                'name' => 'Interior Construction',
                'status' => 'Upcoming',
                'progress' => 0,
                'milestones_count' => 0,
                'sub_milestones_count' => 0,
                'checkpoints' => [
                    ['title' => 'Drywall Framing & Insulation', 'done' => false],
                    ['title' => 'MEP Rough-in Installation', 'done' => false],
                ]
            ],
            [
                'name' => 'Exterior Finishing Works',
                'status' => 'Upcoming',
                'progress' => 0,
                'milestones_count' => 0,
                'sub_milestones_count' => 0,
                'checkpoints' => [
                    ['title' => 'Curtain Wall Glass Installation', 'done' => false],
                ]
            ],
            [
                'name' => 'Exterior construction',
                'status' => 'Upcoming',
                'progress' => 0,
                'milestones_count' => 0,
                'sub_milestones_count' => 0,
                'checkpoints' => [
                    ['title' => 'Hardscaping & Paving', 'done' => false],
                ]
            ],
            [
                'name' => 'Post Construction',
                'status' => 'Upcoming',
                'progress' => 0,
                'milestones_count' => 0,
                'sub_milestones_count' => 0,
                'checkpoints' => [
                    ['title' => 'Snag List Clearing & Sign-off', 'done' => false],
                ]
            ],
            [
                'name' => 'Cost Tracking',
                'status' => 'In Progress',
                'progress' => 50,
                'milestones_count' => 3,
                'sub_milestones_count' => 12,
                'checkpoints' => [
                    ['title' => 'Interim Payment Certificate #3', 'done' => true],
                    ['title' => 'Budget Audit Review', 'done' => false],
                ]
            ],
        ];

        foreach ($phasesData as $phaseData) {
            $phase = ProjectPhase::create([
                'project_detail_id' => $detailId,
                'name' => $phaseData['name'],
                'status' => $phaseData['status'],
                'progress' => $phaseData['progress'],
                'milestones_count' => $phaseData['milestones_count'],
                'sub_milestones_count' => $phaseData['sub_milestones_count'],
            ]);

            foreach ($phaseData['checkpoints'] as $checkpointData) {
                MilestoneCheckpoint::create([
                    'project_phase_id' => $phase->id,
                    'track_id' => $trackId,
                    'parent_id' => null, // Just keeping it simple as top level for now
                    'title' => $checkpointData['title'],
                    'done' => $checkpointData['done'],
                ]);
            }
        }
    }
}

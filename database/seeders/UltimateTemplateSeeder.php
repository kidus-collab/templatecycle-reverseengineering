<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template\ProjectTemplate;
use App\Models\Template\Track;
use App\Models\Template\Milestone;
use Faker\Factory as Faker;

class UltimateTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Target numbers
        $targetTracks = 3;
        $targetPhases = 16;
        $targetMilestones = 128;
        $targetSubMilestones = 726;

        $tpl = ProjectTemplate::create([
            'name' => 'Ultimate Project Template',
            'description' => 'Comprehensive template with 3 tracks, 16 phases, 128 milestones, and 726 submilestones.',
            'tracks_count' => $targetTracks,
            'milestones_count' => $targetPhases + $targetMilestones,
            'sub_milestones_count' => $targetSubMilestones,
            'is_active' => true,
        ]);

        $trackNames = ['Construction Track', 'Design & Architecture', 'Supervision & QC'];
        $tracks = [];

        // Create 3 Tracks
        for ($i = 0; $i < $targetTracks; $i++) {
            $tracks[] = Track::create([
                'project_template_id' => $tpl->id,
                'name' => $trackNames[$i],
            ]);
        }

        // Distribute 16 phases across 3 tracks
        $phasesPerTrack = [5, 6, 5];
        $phases = [];
        foreach ($tracks as $index => $track) {
            for ($p = 0; $p < $phasesPerTrack[$index]; $p++) {
                $phases[] = Milestone::create([
                    'track_id' => $track->id,
                    'parent_id' => null,
                    'title' => 'Phase: ' . $faker->catchPhrase(),
                ]);
            }
        }

        // Distribute 128 milestones across 16 phases (exactly 8 per phase)
        $milestones = [];
        foreach ($phases as $phase) {
            for ($m = 0; $m < 8; $m++) {
                $milestones[] = Milestone::create([
                    'track_id' => $phase->track_id,
                    'parent_id' => $phase->id,
                    'title' => 'Milestone: ' . $faker->words(3, true),
                ]);
            }
        }

        // Distribute 726 sub-milestones across 128 milestones
        // 726 / 128 = 5 remainder 86
        // So 86 milestones get 6, and 42 get 5.
        $subMilestonesCount = 0;
        foreach ($milestones as $index => $milestone) {
            $count = ($index < 86) ? 6 : 5;
            for ($s = 0; $s < $count; $s++) {
                Milestone::create([
                    'track_id' => $milestone->track_id,
                    'parent_id' => $milestone->id,
                    'title' => 'Task: ' . $faker->sentence(4),
                ]);
                $subMilestonesCount++;
            }
        }
    }
}

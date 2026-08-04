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
        $projects = Project::with('details')->get()->map(function ($project) {
            
            // Recreating the original hardcoded dummy summary text for demo purposes
            $milestones = '0/128';
            $subMilestones = '0/723';
            
            if ($project->ref === 'MC26-L-G-1-MT1') {
                $milestones = '54/128';
                $subMilestones = '310/723';
            } elseif ($project->ref === 'MC26-L-G1-AT1') {
                $milestones = '2/7';
                $subMilestones = '4/10';
            } elseif ($project->ref === 'PM-ACTIVE-002') {
                $milestones = '18/128';
                $subMilestones = '95/723';
            } elseif ($project->ref === 'PM-ACTIVE-003') {
                $milestones = '12/24';
                $subMilestones = '45/100';
            } elseif ($project->ref === 'PM-HANDOVER-004') {
                $milestones = '60/60';
                $subMilestones = '200/200';
            }

            return [
                'ref' => $project->ref,
                'name' => $project->name,
                'tags' => $project->tags ?? [],
                'moreTags' => $project->more_tags ?? '',
                'milestones' => $milestones,
                'subMilestones' => $subMilestones,
                'progressPercent' => $project->details->progress_percent ?? 0,
                'status' => $project->details->status ?? 'Upcoming',
            ];
        });

        return view('projects.index', compact('projects'));
    }

    /**
     * Display single project detail view with stage template progress milestones.
     */
    public function show($ref)
    {
        $projectModel = Project::with(['details', 'phases.milestoneCheckpoints'])
            ->where('ref', $ref)
            ->firstOrFail();

        $phases = $projectModel->phases->map(function ($phase) {
            $checkpoints = $phase->milestoneCheckpoints->map(function ($cp) {
                return [
                    'title' => $cp->title,
                    'done' => $cp->done,
                ];
            })->toArray();

            return [
                'id' => $phase->id,
                'name' => $phase->name,
                'status' => $phase->status,
                'progress' => $phase->progress,
                'milestonesCount' => $phase->milestones_count . '/' . ($phase->milestones_count > 0 ? $phase->milestones_count : 0), 
                'subMilestonesCount' => $phase->sub_milestones_count . '/' . ($phase->sub_milestones_count > 0 ? $phase->sub_milestones_count : 0),
                'checkpoints' => $checkpoints,
            ];
        })->toArray();

        // If phases are empty (for non-Mesob projects in our seed), provide the fallback from original array
        if (empty($phases)) {
            $phases = [
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
            ];
        }

        // Recreating original hardcoded strings for milestones
        $milestones = '0/128';
        $subMilestones = '0/723';
        if ($projectModel->ref === 'MC26-L-G-1-MT1') {
            $milestones = '54/128';
            $subMilestones = '310/723';
            
            // Adjust milestone counts for the exact strings in the original array
            $phases[0]['milestonesCount'] = '16/16';
            $phases[0]['subMilestonesCount'] = '90/90';
            $phases[1]['milestonesCount'] = '16/24';
            $phases[1]['subMilestonesCount'] = '91/140';
            $phases[2]['milestonesCount'] = '0/32';
            $phases[2]['subMilestonesCount'] = '0/180';
            $phases[3]['milestonesCount'] = '0/20';
            $phases[3]['subMilestonesCount'] = '0/110';
            $phases[4]['milestonesCount'] = '0/12';
            $phases[4]['subMilestonesCount'] = '0/80';
            $phases[5]['milestonesCount'] = '0/10';
            $phases[5]['subMilestonesCount'] = '0/60';
            $phases[6]['milestonesCount'] = '0/8';
            $phases[6]['subMilestonesCount'] = '0/40';
            $phases[7]['milestonesCount'] = '3/6';
            $phases[7]['subMilestonesCount'] = '12/23';
        }

        $project = [
            'ref' => $projectModel->ref,
            'name' => $projectModel->name,
            'appliedTrack' => $projectModel->details->applied_track ?? 'Construction Track',
            'progressPercent' => $projectModel->details->progress_percent ?? 0,
            'milestones' => $milestones,
            'subMilestones' => $subMilestones,
            'status' => $projectModel->details->status ?? 'Upcoming',
            'phases' => $phases,
        ];

        return view('projects.show', compact('project'));
    }
}

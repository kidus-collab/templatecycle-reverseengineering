@props(['project'])

@php
    $hasTemplate = !empty($project->project_template_id);
@endphp

<div class="bg-white rounded-xl border border-gray-200/90 p-4 shadow-2xs hover:shadow-md hover:border-amber-300/80 transition-all duration-200 flex flex-col justify-between space-y-3 group">
    
    <!-- Card Header: Ref Code & Single Applied Track Tag -->
    <div class="flex items-center justify-between text-[10px] font-mono text-gray-400">
        <span class="font-medium text-gray-500">{{ $project->ref }}</span>
        @if($hasTemplate)
            <span class="bg-amber-100/80 text-amber-900 px-1.5 py-0.2 rounded font-sans text-[9px] font-medium border border-amber-200" title="Applied Template">1 Template Applied</span>
        @endif
    </div>

    <!-- Card Body: Clean Project Title & Tags -->
    <div class="space-y-2">
        <h3 class="font-bold text-base text-gray-900 group-hover:text-amber-900 transition-colors">{{ $project->name }}</h3>
        
        <!-- Tags List -->
        <div class="flex flex-wrap items-center gap-1">
            @if($hasTemplate && $project->projectTemplate)
                <span class="text-[9px] font-medium bg-amber-50 text-amber-900 border border-amber-200/80 px-1.5 py-0.2 rounded">{{ $project->projectTemplate->name }}</span>
            @elseif($project->details && $project->details->applied_track && $project->details->applied_track !== 'No Template')
                <span class="text-[9px] font-medium bg-amber-50 text-amber-900 border border-amber-200/80 px-1.5 py-0.2 rounded">{{ $project->details->applied_track }}</span>
            @endif
        </div>
    </div>

    <!-- Card Footer: Milestone & Sub-milestone Indicators + Go to project & Edit/Delete Buttons -->
    <div class="pt-3 border-t border-gray-100 space-y-2.5">
        <!-- Milestone & Sub-Milestone Indicators -->
        <div class="grid grid-cols-2 gap-1 text-[10px] font-mono text-gray-500">
            @php
                $appliedTracks = [];
                if ($project->details && $project->details->applied_track) {
                    $appliedTracks = array_map('trim', explode(',', $project->details->applied_track));
                }
                
                $totalMilestones = $project->phases->sum('milestones_count');
                $totalSubMilestones = $project->phases->sum('sub_milestones_count');
                
                // Estimate completed based on average progress
                $avgProgress = $project->phases->count() > 0 ? $project->phases->avg('progress') / 100 : 0;
                $completedMilestones = round($totalMilestones * $avgProgress);
                $completedSubMilestones = round($totalSubMilestones * $avgProgress);
            @endphp
            <div>
                <span>Milestones: </span>
                <span class="font-semibold text-gray-800">{{ $completedMilestones }}/{{ $totalMilestones }}</span>
            </div>
            <div>
                <span>Sub-milestones: </span>
                <span class="font-semibold text-gray-800">{{ $completedSubMilestones }}/{{ $totalSubMilestones }}</span>
            </div>
        </div>

        <!-- Footer Actions Bar: Go to project (left) + Pencil & Trashbin (right) -->
        <div class="flex items-center justify-between pt-0.5">
            <!-- Go to project Button -->
            <a 
                href="{{ route('projects.show', $project->ref) }}"
                class="btn btn-xs px-3 bg-amber-100/80 hover:bg-amber-200 text-amber-950 border border-amber-300/90 rounded-lg text-[11px] font-semibold normal-case inline-flex items-center justify-center gap-1 shadow-2xs py-1">
                <span>Go to project</span>
                <i data-lucide="arrow-right" class="w-3 h-3 text-amber-800"></i>
            </a>

            <!-- Pencil Edit & Trashbin Delete Actions (Right Side of Card Footer) -->
            <div class="flex items-center gap-1">
                <button 
                    type="button"
                    @click="openEditModal({{ json_encode([
                        'ref' => $project->ref, 
                        'name' => $project->name, 
                        'status' => $project->details->status ?? 'Upcoming'
                    ]) }})"
                    class="p-1.5 text-gray-400 hover:text-amber-900 hover:bg-amber-100 rounded-lg transition-colors flex items-center justify-center"
                    title="Edit project">
                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </button>
                <button 
                    type="button"
                    @click="openDeleteModal({{ json_encode([
                        'ref' => $project->ref, 
                        'name' => $project->name
                    ]) }})"
                    class="p-1.5 text-gray-400 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors flex items-center justify-center"
                    title="Delete project">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </div>
    </div>

</div>

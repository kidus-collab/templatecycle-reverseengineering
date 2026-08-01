@props(['project'])

<div class="bg-white rounded-xl border border-gray-200/90 p-4 shadow-2xs hover:shadow-md hover:border-amber-300/80 transition-all duration-200 flex flex-col justify-between space-y-3 group">
    
    <!-- Card Header: Ref Code & Single Applied Track Tag -->
    <div class="flex items-center justify-between text-[10px] font-mono text-gray-400">
        <span class="font-medium">{{ $project['ref'] }}</span>
        <span class="bg-amber-100/80 text-amber-900 px-1.5 py-0.2 rounded font-sans text-[9px] font-medium border border-amber-200" title="Applied Track">1 Track Applied</span>
    </div>

    <!-- Card Body: Clean Project Title & Tags -->
    <div class="space-y-2">
        <h3 class="font-bold text-base text-gray-900 group-hover:text-amber-900 transition-colors">{{ $project['name'] }}</h3>
        
        <!-- Tags List -->
        <div class="flex flex-wrap items-center gap-1">
            @foreach($project['tags'] as $tag)
                <span class="text-[9px] font-medium bg-amber-50 text-amber-900 border border-amber-200/80 px-1.5 py-0.2 rounded">{{ $tag }}</span>
            @endforeach
            @if(!empty($project['moreTags']))
                <span class="text-[9px] text-gray-400 font-medium">{{ $project['moreTags'] }}</span>
            @endif
        </div>
    </div>

    <!-- Card Footer: Milestone & Sub-milestone Indicators + Go to project button -->
    <div class="pt-3 border-t border-gray-100 space-y-2">
        <!-- Milestone & Sub-Milestone Indicators -->
        <div class="grid grid-cols-2 gap-1 text-[10px] font-mono text-gray-500">
            <div>
                <span>Milestones: </span>
                <span class="font-semibold text-gray-800">{{ $project['milestones'] }}</span>
            </div>
            <div>
                <span>Sub-milestones: </span>
                <span class="font-semibold text-gray-800">{{ $project['subMilestones'] }}</span>
            </div>
        </div>

        <!-- Go to project Button -->
        <a 
            href="{{ route('projects.show', $project['ref']) }}"
            class="btn btn-xs w-full bg-amber-100/80 hover:bg-amber-200 text-amber-950 border border-amber-300/90 rounded-lg text-[11px] font-semibold normal-case flex items-center justify-center gap-1 shadow-2xs py-1">
            <span>Go to project</span>
            <i data-lucide="arrow-right" class="w-3 h-3 text-amber-800"></i>
        </a>
    </div>

</div>

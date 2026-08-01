<x-app-layout title="Project Details - {{ $project['name'] }}">

    <div class="space-y-4">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="text-[10px] font-semibold tracking-wider text-amber-700/70 uppercase mb-0.5 flex items-center gap-1">
                    <span>PROJECT PORTFOLIO</span>
                    <span>•</span>
                    <span>{{ $project['ref'] }}</span>
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">{{ $project['name'] }}</h1>
            </div>

            <a href="{{ route('projects.index') }}" class="btn btn-outline btn-xs rounded-lg font-medium normal-case flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Back to Portfolio</span>
            </a>
        </header>

        <!-- Project Overview Box -->
        <div class="bg-white rounded-xl border border-gray-200/90 p-5 shadow-2xs space-y-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-mono text-gray-500">REF: {{ $project['ref'] }}</span>
                <span class="bg-amber-100 text-amber-900 text-[10px] font-medium px-2 py-0.5 rounded-full">1 Track Applied</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-metric-card label="MILESTONES" :value="$project['milestones']" subtext="Completed checkpoints" />
                <x-metric-card label="SUB-MILESTONES" :value="$project['subMilestones']" subtext="Detailed stage checks" />
                <x-metric-card label="DELIVERY STAGE" value="Construction" subtext="1 track assigned" />
            </div>
        </div>
    </div>

</x-app-layout>

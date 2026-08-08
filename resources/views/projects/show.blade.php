<x-app-layout title="Project Progress - {{ $project->name }}">

    <div class="space-y-4" x-data="projectDetail()">
        
        <!-- Header & Breadcrumb -->
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="text-[10px] font-semibold tracking-wider text-amber-700/70 uppercase mb-0.5 flex items-center gap-1">
                    <span>PROJECT PORTFOLIO</span>
                    <span>•</span>
                    <span>{{ $project->ref }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">{{ $project->name }}</h1>
                    <span class="bg-amber-100 text-amber-900 text-[10px] font-medium px-2 py-0.5 rounded-full border border-amber-200" title="Applied Template">
                        Template: {{ $project->projectTemplate->name ?? ($project->details->applied_track ?? 'No Template') }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons: Back -->
            <div class="flex items-center gap-2 self-start sm:self-center">
                <a href="{{ route('projects.index') }}" class="btn btn-outline btn-xs rounded-lg font-medium normal-case flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Portfolio</span>
                </a>
            </div>
        </header>

        <!-- Project Progress Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <!-- Overall Progress Bar Card -->
            <div class="bg-white p-3.5 rounded-xl border border-gray-200/90 shadow-2xs">
                <div class="text-[9px] font-bold tracking-widest text-gray-400 uppercase">OVERALL PROGRESS</div>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-xl font-bold text-gray-900" x-text="overallProgress + '%'"></span>
                    <span class="text-[10px] font-semibold text-amber-800 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200" x-text="overallStatus"></span>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full transition-all duration-300" :style="`width: ${overallProgress}%`"></div>
                </div>
            </div>

            <!-- Milestones Counter Card -->
            <x-metric-card label="MILESTONES COMPLETE" :value="$project->phases->sum('progress') > 0 ? $project->phases->sum('milestones_count') . '/128' : '0/128'" subtext="Major stage checkpoints" />

            <!-- Sub-milestones Counter Card -->
            <x-metric-card label="SUB-MILESTONES COMPLETE" :value="$project->phases->sum('progress') > 0 ? $project->phases->sum('sub_milestones_count') . '/723' : '0/723'" subtext="Detailed stage checks" />

            <!-- Applied Template Card -->
            <x-metric-card label="APPLIED TEMPLATE" :value="$project->projectTemplate->name ?? ($project->details->applied_track ?? 'No Template')" subtext="Structured delivery framework" />
        </div>

        <!-- TEMPLATE PROGRESS MILESTONES SECTION -->
        <div class="space-y-3 pt-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">STAGE TEMPLATE PROGRESS MILESTONES</h2>
                    <p class="text-[11px] text-gray-500">Phases and milestone checkpoints derived from the active template stage track.</p>
                </div>

                <div class="flex items-center gap-3">
                    <span x-show="hasUnsavedChanges" x-cloak class="text-[10px] font-bold text-amber-600 bg-amber-100/50 px-2 py-1 rounded-md animate-pulse">Unsaved Changes</span>
                    <button @click="saveProgress" :disabled="isSaving || !hasUnsavedChanges" class="btn btn-xs bg-amber-100/90 hover:bg-amber-200 text-amber-950 border border-amber-300 rounded-lg font-semibold normal-case">
                        <span x-text="isSaving ? 'Saving...' : 'Save Progress'"></span>
                    </button>
                </div>
            </div>

            <!-- Phase Milestones Sequence List -->
            <div class="space-y-3">
                <template x-for="(phase, index) in phases" :key="phase.id">
                    <div class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden transition-all duration-150">
                        
                        <!-- Phase Header Bar -->
                        <div class="p-3.5 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                            
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="text-xs font-bold text-gray-400 font-mono" x-text="'0' + (index + 1)"></span>
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900" x-text="phase.name"></h3>
                                    <div class="flex items-center gap-2 text-[10px] font-mono text-gray-500 mt-0.5">
                                        <span>Milestones: <strong class="text-gray-800" x-text="phase.milestonesCount"></strong></span>
                                        <span>·</span>
                                        <span>Sub-milestones: <strong class="text-gray-800" x-text="phase.subMilestonesCount"></strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Status & Progress Badge -->
                            <div class="flex items-center gap-3 shrink-0">
                                <!-- Progress % Pill -->
                                <div class="text-right hidden sm:block">
                                    <span class="text-xs font-bold text-gray-800" x-text="phase.progress + '%'"></span>
                                    <div class="w-20 bg-gray-100 h-1.5 rounded-full mt-1">
                                        <div class="bg-amber-500 h-full rounded-full" :style="'width: ' + phase.progress + '%'"></div>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <span 
                                    :class="{
                                        'bg-emerald-50 text-emerald-700 border-emerald-200': phase.status === 'Completed',
                                        'bg-amber-50 text-amber-900 border-amber-300': phase.status === 'In Progress',
                                        'bg-gray-100 text-gray-600 border-gray-200': phase.status === 'Upcoming'
                                    }"
                                    class="text-[10px] font-semibold px-2 py-0.5 rounded-full border" 
                                    x-text="phase.status"></span>

                                <!-- Expand Chevron Icon Button -->
                                <button type="button" @click.stop="phase.expanded = !phase.expanded" class="p-1.5 rounded-md bg-white hover:bg-amber-100 text-gray-500 hover:text-amber-700 transition-colors border border-gray-200 shadow-sm flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-amber-200 cursor-pointer">
                                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': phase.expanded}"></i>
                                </button>
                            </div>

                        </div>

                        <!-- Expandable Checkpoints Checklist -->
                        <div x-show="phase.expanded" x-cloak class="border-t border-gray-100 bg-gray-50/50 p-4 space-y-2">
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">STAGE CHECKPOINTS</div>
                            
                            <div class="space-y-3">
                                <!-- Top Level Milestones Only -->
                                <template x-for="cp in phase.checkpoints.filter(c => !c.parent_id)" :key="cp.title">
                                    <div class="space-y-1.5">
                                        <!-- Top Level Milestone Checkbox & Expand Toggle -->
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-gray-200/80 hover:border-amber-300 transition-colors shadow-2xs">
                                            <label class="flex items-center gap-2.5 cursor-pointer flex-1">
                                                <input type="checkbox" x-model="cp.done" @change="toggleParent(cp, phase)" class="checkbox checkbox-sm checkbox-warning" />
                                                <span class="text-xs font-bold text-gray-800" :class="{'line-through text-gray-400': cp.done}" x-text="cp.title"></span>
                                            </label>
                                            
                                            <div class="flex items-center gap-2 shrink-0">
                                                <!-- Expanded State Toggle -->
                                                <button type="button" x-show="cp.subCheckpoints && cp.subCheckpoints.length > 0" @click.stop="cp.expanded = !cp.expanded" class="p-1 rounded-md bg-white hover:bg-amber-100 text-gray-500 hover:text-amber-700 transition-colors border border-gray-200 shadow-sm flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-amber-200 cursor-pointer">
                                                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="{'rotate-180': !cp.expanded}"></i>
                                                </button>
                                                <!-- Fallback Badge for ones without submilestones -->
                                                <span x-show="!cp.subCheckpoints || cp.subCheckpoints.length === 0" :class="cp.done ? 'text-emerald-600 bg-emerald-50' : 'text-gray-400 bg-gray-100'" class="text-[9px] font-mono px-1.5 py-0.2 rounded border" :class="cp.done ? 'border-emerald-200' : 'border-gray-200'" x-text="cp.done ? 'Done' : 'Pending'"></span>
                                            </div>
                                        </div>

                                        <!-- Sub-Milestones Checkboxes -->
                                        <div x-show="cp.expanded && cp.subCheckpoints && cp.subCheckpoints.length > 0" class="pl-5 space-y-1 mt-1 border-l-2 border-amber-100 ml-4 relative">
                                            <template x-for="sub in cp.subCheckpoints" :key="sub.title">
                                                <label class="flex items-center justify-between p-1.5 rounded-lg bg-white/60 border border-gray-100 hover:border-amber-200 hover:bg-amber-50/30 cursor-pointer transition-colors relative">
                                                    <!-- Little tree connector line -->
                                                    <div class="absolute -left-5 top-1/2 w-4 border-t-2 border-amber-100"></div>
                                                    
                                                    <div class="flex items-center gap-2 relative z-10">
                                                        <input type="checkbox" x-model="sub.done" @change="toggleChild(cp, phase)" class="checkbox checkbox-xs checkbox-warning rounded-sm" />
                                                        <span class="text-[11px] font-medium text-gray-600" :class="{'line-through text-gray-400': sub.done}" x-text="sub.title"></span>
                                                    </div>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>

    </div>

    <script>
        function projectDetail() {
            return {
                isSaving: false,
                hasUnsavedChanges: false,
                
                init() {
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                        setTimeout(() => {
                            if (window.lucide) window.lucide.createIcons();
                        }, 50);
                    });
                },
                phases: @json($project->phases).map(p => {
                    const allCheckpoints = p.milestone_checkpoints || p.milestoneCheckpoints || [];
                    
                    // Wire up the nested sub-checkpoints reference array to the top-level parent checkpoints
                    allCheckpoints.forEach(cp => {
                        if (!cp.parent_id) {
                            cp.expanded = true; // default to expanded
                            cp.subCheckpoints = allCheckpoints.filter(sub => sub.parent_id === cp.id);
                        }
                    });

                    return { 
                        ...p, 
                        expanded: p.status === 'In Progress',
                        milestonesCount: p.milestones_count + '/' + (p.milestones_count > 0 ? p.milestones_count : 0),
                        subMilestonesCount: p.sub_milestones_count + '/' + (p.sub_milestones_count > 0 ? p.sub_milestones_count : 0),
                        checkpoints: allCheckpoints
                    };
                }),
                
                get overallProgress() {
                    if (this.phases.length === 0) return 0;
                    const total = this.phases.reduce((sum, p) => sum + p.progress, 0);
                    return Math.round(total / this.phases.length);
                },
                
                get overallStatus() {
                    const prog = this.overallProgress;
                    if (prog === 100) return 'Completed';
                    if (prog > 0) return 'In Progress';
                    return 'Upcoming';
                },

                toggleParent(cp, phase) {
                    // Ticking parent ticks/unticks all sub-milestones
                    if (cp.subCheckpoints && cp.subCheckpoints.length > 0) {
                        cp.subCheckpoints.forEach(sub => sub.done = cp.done);
                    }
                    this.recalculatePhaseProgress(phase);
                },
                
                toggleChild(parentCp, phase) {
                    // Ticking a child checks if all children are done to update the parent
                    if (parentCp.subCheckpoints && parentCp.subCheckpoints.length > 0) {
                        const allDone = parentCp.subCheckpoints.every(sub => sub.done);
                        parentCp.done = allDone;
                    }
                    this.recalculatePhaseProgress(phase);
                },

                recalculatePhaseProgress(phase) {
                    this.hasUnsavedChanges = true;
                    const total = phase.checkpoints.length;
                    if (total === 0) return;
                    const doneCount = phase.checkpoints.filter(c => c.done).length;
                    phase.progress = Math.round((doneCount / total) * 100);
                    if (phase.progress === 100) phase.status = 'Completed';
                    else if (phase.progress > 0) phase.status = 'In Progress';
                    else phase.status = 'Upcoming';
                },
                
                async saveProgress() {
                    if (this.isSaving) return;
                    this.isSaving = true;
                    
                    try {
                        const payload = this.phases.map(p => ({
                            id: p.id,
                            status: p.status,
                            progress: p.progress,
                            checkpoints: p.checkpoints.map(c => ({
                                id: c.id,
                                done: Boolean(c.done)
                            }))
                        }));

                        const res = await fetch(`/projects/{{ $project->ref }}/progress`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ phases: payload })
                        });
                        
                        if (!res.ok) {
                            throw new Error('Failed to save progress');
                        }
                        
                        // We successfully saved! Remove unsaved changes badge
                        this.hasUnsavedChanges = false;
                        
                    } catch (err) {
                        alert('Error saving progress.');
                        console.error(err);
                    } finally {
                        this.isSaving = false;
                    }
                }
            }
        }
    </script>

</x-app-layout>

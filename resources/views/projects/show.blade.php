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
                    <span class="bg-amber-100 text-amber-900 text-[10px] font-medium px-2 py-0.5 rounded-full border border-amber-200" title="Applied Track">
                        1 Track: {{ $project->details->applied_track ?? 'Construction Track' }}
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
                    <span class="text-xl font-bold text-gray-900">{{ $project->details->progress_percent ?? 0 }}%</span>
                    <span class="text-[10px] font-semibold text-amber-800 bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200">{{ $project->details->status ?? 'Upcoming' }}</span>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full transition-all duration-300" style="width: {{ $project->details->progress_percent ?? 0 }}%"></div>
                </div>
            </div>

            <!-- Milestones Counter Card -->
            <x-metric-card label="MILESTONES COMPLETE" :value="$project->phases->sum('progress') > 0 ? $project->phases->sum('milestones_count') . '/128' : '0/128'" subtext="Major stage checkpoints" />

            <!-- Sub-milestones Counter Card -->
            <x-metric-card label="SUB-MILESTONES COMPLETE" :value="$project->phases->sum('progress') > 0 ? $project->phases->sum('sub_milestones_count') . '/723' : '0/723'" subtext="Detailed stage checks" />

            <!-- Applied Track Card -->
            <x-metric-card label="APPLIED TEMPLATE TRACK" :value="$project->details->applied_track ?? 'Construction Track'" subtext="Single track limit enforced" />
        </div>

        <!-- TEMPLATE PROGRESS MILESTONES SECTION -->
        <div class="space-y-3 pt-2">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">STAGE TEMPLATE PROGRESS MILESTONES</h2>
                    <p class="text-[11px] text-gray-500">Phases and milestone checkpoints derived from the active template stage track.</p>
                </div>

                <button @click="saveProgress" :disabled="isSaving" class="btn btn-xs bg-amber-100/90 hover:bg-amber-200 text-amber-950 border border-amber-300 rounded-lg font-semibold normal-case">
                    <span x-text="isSaving ? 'Saving...' : 'Save Progress'"></span>
                </button>
            </div>

            <!-- Phase Milestones Sequence List -->
            <div class="space-y-3">
                <template x-for="(phase, index) in phases" :key="phase.id">
                    <div class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden transition-all duration-150">
                        
                        <!-- Phase Header Bar -->
                        <div 
                            @click="phase.expanded = !phase.expanded"
                            class="p-3.5 flex items-center justify-between cursor-pointer hover:bg-amber-50/30 transition-colors">
                            
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

                                <!-- Expand Chevron Icon -->
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{'rotate-180': phase.expanded}"></i>
                            </div>

                        </div>

                        <!-- Expandable Checkpoints Checklist -->
                        <div x-show="phase.expanded" x-cloak class="border-t border-gray-100 bg-gray-50/50 p-4 space-y-2">
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">STAGE CHECKPOINTS</div>
                            
                            <div class="space-y-1.5">
                                <template x-for="cp in phase.checkpoints" :key="cp.title">
                                    <label class="flex items-center justify-between p-2 rounded-lg bg-white border border-gray-200/80 hover:border-amber-300 cursor-pointer transition-colors">
                                        <div class="flex items-center gap-2.5">
                                            <input type="checkbox" x-model="cp.done" @change="recalculatePhaseProgress(phase)" class="checkbox checkbox-xs checkbox-warning" />
                                            <span class="text-xs font-medium text-gray-800" :class="{'line-through text-gray-400': cp.done}" x-text="cp.title"></span>
                                        </div>
                                        <span :class="cp.done ? 'text-emerald-600 bg-emerald-50' : 'text-gray-400 bg-gray-100'" class="text-[9px] font-mono px-1.5 py-0.2 rounded" x-text="cp.done ? 'Done' : 'Pending'"></span>
                                    </label>
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
                phases: @json($project->phases).map(p => ({ 
                    ...p, 
                    expanded: p.status === 'In Progress',
                    milestonesCount: p.milestones_count + '/' + (p.milestones_count > 0 ? p.milestones_count : 0),
                    subMilestonesCount: p.sub_milestones_count + '/' + (p.sub_milestones_count > 0 ? p.sub_milestones_count : 0),
                    checkpoints: p.milestone_checkpoints || p.milestoneCheckpoints || []
                })),

                recalculatePhaseProgress(phase) {
                    const total = phase.checkpoints.length;
                    if (total === 0) return;
                    const doneCount = phase.checkpoints.filter(c => c.done).length;
                    phase.progress = Math.round((doneCount / total) * 100);
                    if (phase.progress === 100) phase.status = 'Completed';
                    else if (phase.progress > 0) phase.status = 'In Progress';
                    else phase.status = 'Upcoming';
                    
                    // Auto-save progress to the backend immediately when a checkbox is toggled
                    this.saveProgress();
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

                        const response = await fetch(`{{ route('projects.progress.update', $project->id) }}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ phases: payload })
                        });

                        if (response.ok) {
                            if (this.$root && typeof this.$root.showNotification === 'function') {
                                this.$root.showNotification('Milestone checklist saved!');
                            } else {
                                console.log('Progress saved successfully!');
                            }
                        } else {
                            const errData = await response.json();
                            console.error('Validation errors:', errData);
                            alert('Failed to save progress. Check console for details.');
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Error saving progress.');
                    } finally {
                        this.isSaving = false;
                    }
                }
            }
        }
    </script>

</x-app-layout>

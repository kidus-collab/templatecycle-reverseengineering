<x-app-layout title="Project Stage Templates - Project Cycle">
    
    <div class="space-y-4" x-data="templateBuilder()">
        <!-- Top Header Navigation & Action -->
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <!-- Breadcrumb -->
                <div class="text-[10px] font-semibold tracking-wider text-amber-700/70 uppercase mb-0.5 flex items-center gap-1">
                    <span>PROJECT MANAGEMENT</span>
                    <span>•</span>
                    <span>PROJECT STAGE TEMPLATES</span>
                </div>
                <!-- Title -->
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">Project Stage Templates</h1>
                <!-- Subtitle -->
                <p class="text-xs text-gray-500 mt-0.5 max-w-3xl">
                    Set up the reusable delivery stages once, then copy that plan into each new project as a starting point.
                </p>
            </div>

            <!-- Top Right Action Button -->
            <div class="flex flex-wrap items-center gap-2 self-start sm:self-center">
                <button 
                    @click="applyToProject()" 
                    class="btn bg-amber-100/90 hover:bg-amber-200 text-amber-950 border border-amber-300/80 hover:border-amber-400 rounded-lg px-4 py-1.5 text-xs font-semibold shadow-xs transition-all duration-150 normal-case flex items-center gap-1.5">
                    <span>Apply to project</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-amber-800"></i>
                </button>
            </div>
        </header>

        <!-- Layout Grid: Left Sidebar & Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start mt-4">

            <!-- LEFT COLUMN: Templates List -->
            <aside class="lg:col-span-3 bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-gray-200/80 shadow-xs space-y-3 relative z-50">
                <div>
                    <span class="text-[9px] font-bold tracking-widest text-gray-400 uppercase">TEMPLATES</span>
                    <h2 class="text-sm font-semibold text-gray-900 mt-0.5">Choose a stage template to edit</h2>
                    <p class="text-[11px] text-gray-500 mt-0.5">Used when a project is first created or refreshed from Project details.</p>
                </div>

                <!-- Create New Template Button -->
                <button 
                    @click="openCreateModal()" 
                    class="w-full px-4 btn btn-outline btn-xs text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400 font-medium normal-case rounded-lg flex items-center justify-center gap-1.5 py-1.5">
                    <i data-lucide="plus" class="w-3.5 h-3.5 text-gray-600"></i>
                    <span>Create new template</span>
                </button>

                <!-- Template Cards List -->
                <div class="space-y-2.5 pt-0.5 max-h-[700px] overflow-y-auto pr-1">
                    <template x-for="template in templates" :key="template.id">
                        <div 
                            @click="selectTemplate(template)"
                            :class="{
                                'bg-amber-50/40 border-amber-300/90 ring-1 ring-amber-400/50 shadow-2xs': activeTemplate && activeTemplate.id === template.id,
                                'bg-white border-gray-200 hover:border-gray-300 hover:bg-gray-50/50': !activeTemplate || activeTemplate.id !== template.id
                            }"
                            class="p-3 rounded-lg border transition-all duration-200 cursor-pointer relative group">
                            
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="font-semibold text-xs text-gray-900 group-hover:text-amber-900 transition-colors" x-text="template.name"></h3>
                                <span x-show="template.is_active" class="text-[9px] font-semibold text-amber-700 bg-amber-100/80 px-1.5 py-0.2 rounded-full uppercase tracking-wider">Active</span>
                            </div>

                            <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2 leading-relaxed" x-text="template.description"></p>

                            <div class="mt-2 text-[10px] font-medium text-gray-500 flex items-center gap-1 font-mono">
                                <span x-text="template.tracks_count + 'T'"></span>
                                <span>·</span>
                                <span x-text="(template.tracks ? template.tracks.reduce((acc, t) => acc + (t.milestones ? t.milestones.filter(m=>!m.parent_id).length : 0), 0) : 0) + 'P'"></span>
                                <span>·</span>
                                <span x-text="template.milestones_count + 'M'"></span>
                                <span>·</span>
                                <span x-text="template.sub_milestones_count + 'SM'"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </aside>

            <!-- RIGHT MAIN PANEL: Metrics & Tracks/Phases Editor -->
            <main class="lg:col-span-9 space-y-4">

                <!-- Top Metrics Bar Components -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <x-metric-card label="TRACKS" x-text="activeTemplate ? activeTemplate.tracks_count : 0" subtext="1 active stages" />
                    <x-metric-card label="PHASES" x-text="activeTemplate && activeTemplate.tracks ? activeTemplate.tracks.reduce((acc, t) => acc + (t.milestones ? t.milestones.filter(m=>!m.parent_id).length : 0), 0) : 0" subtext="Ordered stage sequence" />
                    <x-metric-card label="MILESTONES" x-text="activeTemplate ? activeTemplate.milestones_count : 0" subtext="Major checkpoints" />
                    <x-metric-card label="SUB-MILESTONES" x-text="activeTemplate ? activeTemplate.sub_milestones_count : 0" subtext="Detailed stage checks" />
                </div>

                <!-- Tracks & Phases Main Editor Box -->
                <div class="bg-white rounded-xl border border-gray-200/90 shadow-xs p-4 md:p-5 min-h-[600px]">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start h-full">

                        <!-- Inner Left: Track Selector -->
                        <div class="md:col-span-4 space-y-3 border-b md:border-b-0 md:border-r border-gray-100 pb-3 md:pb-0 md:pr-3 min-h-full relative">
                            <template x-if="activeTemplate">
                                <div class="space-y-1">
                                    <template x-for="track in activeTemplate.tracks" :key="track.id">
                                        <button 
                                            @click="activeTrackId = track.id"
                                            :class="{
                                                'bg-amber-50/50 border-amber-300 shadow-2xs': activeTrackId === track.id,
                                                'bg-white border-transparent text-gray-700 hover:bg-gray-50': activeTrackId !== track.id
                                            }"
                                            class="w-full flex items-center justify-between px-3 py-2 rounded-lg border text-xs transition-all duration-150 text-left relative">
                                            
                                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                                <span x-show="editingTrackId !== track.id" @click.stop="editTrack(track)" x-text="track.name" class="font-semibold truncate cursor-text"></span>
                                                <input x-show="editingTrackId === track.id" x-model="track.name" @click.away="saveTrack(track)" @keyup.enter="saveTrack(track)" class="input input-xs border-amber-300 focus:border-amber-500 focus:outline-none focus:ring-0 rounded font-semibold w-full bg-white" :id="'track-input-'+track.id">
                                            </div>
                                            
                                            <span :class="activeTrackId === track.id ? 'bg-amber-900/10 text-amber-900' : 'bg-gray-400 text-white'" class="text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center shrink-0 ml-2" 
                                                x-text="getPhases(track).length"></span>
                                        </button>
                                    </template>

                                    <!-- Manage Tracks Dropdown -->
                                    <div class="relative mt-4">
                                        <button @click="managingTracks = !managingTracks" @click.away="managingTracks = false" class="w-full p-2.5 rounded-lg border border-dashed border-gray-300 hover:border-amber-400 hover:bg-amber-50/30 text-[11px] font-medium text-gray-600 hover:text-amber-900 transition-all flex items-center justify-center gap-1.5">
                                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                            <span>Manage tracks</span>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="managingTracks" 
                                             x-transition:enter="transition ease-out duration-300 origin-top"
                                             x-transition:enter-start="opacity-0 -translate-y-2 scale-y-90"
                                             x-transition:enter-end="opacity-100 translate-y-0 scale-y-100"
                                             x-transition:leave="transition ease-in duration-200 origin-top"
                                             x-transition:leave-start="opacity-100 translate-y-0 scale-y-100"
                                             x-transition:leave-end="opacity-0 -translate-y-2 scale-y-90"
                                             class="absolute left-0 top-full mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-200 z-50 p-4">
                                            <div class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-3">DELIVERY TRACKS</div>
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                <button @click="addPredefinedTrack('Design Permit')" class="px-2.5 py-1.5 rounded-md bg-amber-50 border border-amber-100 text-amber-900 text-[11px] font-medium hover:bg-amber-100">Design Permit</button>
                                                <button @click="addPredefinedTrack('Supervision')" class="px-2.5 py-1.5 rounded-md bg-white border border-gray-200 text-gray-700 text-[11px] font-medium hover:bg-gray-50 shadow-xs">Supervision</button>
                                                <button @click="addPredefinedTrack('Construction')" class="px-2.5 py-1.5 rounded-md bg-amber-50 border border-amber-100 text-amber-900 text-[11px] font-medium hover:bg-amber-100">Construction</button>
                                                <button @click="addPredefinedTrack('Post Construction')" class="px-2.5 py-1.5 rounded-md bg-amber-50 border border-amber-100 text-amber-900 text-[11px] font-medium hover:bg-amber-100">Post Construction</button>
                                                <button @click="addPredefinedTrack('Business Development')" class="px-2.5 py-1.5 rounded-md bg-white border border-gray-200 text-gray-700 text-[11px] font-medium hover:bg-gray-50 shadow-xs">Business Development</button>
                                            </div>
                                            <div class="pt-2 border-t border-gray-100 mt-2">
                                                <button @click="addCustomTrack()" class="w-full p-2 border border-dashed border-gray-300 rounded-md text-[11px] font-medium text-gray-500 hover:bg-gray-50 flex items-center justify-center gap-1">
                                                    <i data-lucide="plus" class="w-3 h-3"></i> Add custom track
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>
                        </div>

                        <!-- Inner Right: Phase Items Sequence -->
                        <div class="md:col-span-8">
                            <template x-if="!activeTrack">
                                <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                                    <p class="text-sm font-medium">Select a track to manage its phases.</p>
                                </div>
                            </template>

                            <template x-if="activeTrack">
                                <div>
                                    <!-- Empty State -->
                                    <template x-if="getPhases(activeTrack).length === 0">
                                        <div class="flex flex-col items-center justify-center h-48 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                                            <p class="text-[11px] font-medium text-gray-500 mb-3" x-text="`No phases yet for ${activeTrack.name}.`"></p>
                                            <button @click="addPhase()" class="btn btn-xs bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded shadow-xs normal-case flex items-center gap-1.5">
                                                <i data-lucide="plus" class="w-3.5 h-3.5 text-amber-600"></i> Add first phase
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Phases List -->
                                    <div class="space-y-4">
                                        <template x-for="phase in getPhases(activeTrack)" :key="phase.id">
                                            <div x-data="{ expanded: true }" class="border border-gray-200/90 rounded-xl overflow-hidden shadow-xs bg-white">
                                                
                                                <!-- Phase Header -->
                                                <div class="flex justify-between items-center bg-gray-50/30 p-3 border-b border-gray-100">
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="grip-vertical" class="w-4 h-4 text-gray-300 cursor-move"></i>
                                                        <button @click="expanded = !expanded" class="p-0.5 hover:bg-gray-200 rounded transition-colors">
                                                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500 transition-transform duration-300 ease-in-out" :class="expanded ? '' : '-rotate-90'"></i>
                                                        </button>
                                                        <h3 x-show="editingMilestoneId !== phase.id" @click="editMilestone(phase)" class="font-bold text-sm text-gray-900 cursor-text" x-text="phase.title"></h3>
                                                        <input x-show="editingMilestoneId === phase.id" x-model="phase.title" @click.away="saveMilestone(phase)" @keyup.enter="saveMilestone(phase)" class="input input-xs border-gray-300 focus:border-amber-400 focus:outline-none focus:ring-0 rounded font-bold text-sm h-6 bg-white" :id="'ms-input-'+phase.id">
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <button @click="deleteMilestone(phase)" class="p-1 text-gray-300 hover:text-red-500 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                                    </div>
                                                </div>

                                                <!-- Phase Body (Milestones) -->
                                                <div x-show="expanded" x-collapse.duration.300ms>
                                                    <div class="p-4 space-y-4">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-[9px] font-bold tracking-widest text-gray-500 uppercase">MILESTONES</span>
                                                        <button @click="addChildMilestone(phase.id)" class="text-[10px] font-bold text-gray-500 hover:text-amber-700 flex items-center gap-1 transition-colors uppercase tracking-wider">
                                                            <i data-lucide="plus" class="w-3 h-3"></i> Add
                                                        </button>
                                                    </div>

                                                    <div class="space-y-3">
                                                        <template x-for="ms in getChildren(phase.id)" :key="ms.id">
                                                            <div class="border border-gray-100 rounded-lg p-3 bg-white hover:border-gray-200 transition-colors">
                                                                <div class="flex justify-between items-start">
                                                                    <div class="flex gap-2">
                                                                        <i data-lucide="grip-vertical" class="w-4 h-4 text-gray-200 cursor-move mt-0.5"></i>
                                                                        <div class="min-w-0">
                                                                            <div class="font-semibold text-xs text-gray-800 mb-1 cursor-text" 
                                                                                x-show="editingMilestoneId !== ms.id" @click="editMilestone(ms)" x-text="ms.title"></div>
                                                                            <input x-show="editingMilestoneId === ms.id" x-model="ms.title" @click.away="saveMilestone(ms)" @keyup.enter="saveMilestone(ms)" class="input input-xs border-gray-300 focus:border-amber-400 focus:outline-none focus:ring-0 rounded font-semibold text-xs h-6 mb-1 w-full max-w-[200px] bg-white" :id="'ms-input-'+ms.id">
                                                                            
                                                                            <!-- Sub-milestones -->
                                                                            <div class="space-y-1.5 mt-2 mb-2">
                                                                                <template x-for="sub in getChildren(ms.id)" :key="sub.id">
                                                                                    <div class="flex justify-between items-center group bg-white border border-gray-100 rounded shadow-sm py-1 pr-1 mb-1">
                                                                                        <div class="flex items-center gap-2 pl-2 border-l-2 border-amber-300 min-w-0">
                                                                                            <span x-show="editingMilestoneId !== sub.id" @click="editMilestone(sub)" class="text-[11px] font-medium text-gray-500 cursor-text hover:text-gray-900 truncate" x-text="sub.title"></span>
                                                                                            <input x-show="editingMilestoneId === sub.id" x-model="sub.title" @click.away="saveMilestone(sub)" @keyup.enter="saveMilestone(sub)" class="input input-xs border-gray-200 focus:border-amber-400 focus:outline-none focus:ring-0 rounded h-5 text-[11px] font-medium py-0 px-1 w-full bg-white" :id="'ms-input-'+sub.id">
                                                                                        </div>
                                                                                        <button @click="deleteMilestone(sub)" class="opacity-0 group-hover:opacity-100 p-0.5 text-gray-300 hover:text-red-500 shrink-0"><i data-lucide="trash-2" class="w-3 h-3"></i></button>
                                                                                    </div>
                                                                                </template>
                                                                            </div>
                                                                            
                                                                            <button @click="addChildMilestone(ms.id)" class="text-[10px] font-medium text-gray-400 hover:text-amber-700 flex items-center gap-1 ml-2 transition-colors mt-1.5">
                                                                                <i data-lucide="plus" class="w-3 h-3"></i> Add Sub-milestone
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <button @click="deleteMilestone(ms)" class="p-1 text-gray-300 hover:text-red-500 transition-colors shrink-0"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Add Phase Button at bottom -->
                                        <template x-if="getPhases(activeTrack).length > 0">
                                            <div class="pt-2">
                                                <button @click="addPhase()" class="w-full py-2.5 border border-dashed border-gray-200 rounded-xl text-[11px] font-medium text-gray-500 hover:bg-amber-50/40 hover:border-amber-400 hover:text-amber-900 flex items-center justify-center gap-1.5 transition-all">
                                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add phase
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>

            </main>
        </div>

        <!-- Sticky Footer for Batch Save -->
        <div x-show="activeTemplate" class="sticky bottom-0 -mx-3 md:-mx-6 -mb-3 md:-mb-6 mt-6 bg-white border-t border-gray-200/90 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-40 px-6 py-3 flex items-center justify-between transition-opacity duration-300">
            
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-800" x-text="activeTemplate ? activeTemplate.name : ''"></span>
                <div x-show="hasUnsavedChanges" class="flex items-center gap-1.5" x-transition>
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-[11px] font-medium text-amber-600">Unsaved changes</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="text-[11px] font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                    Back to project register
                </a>
                <button 
                    @click="syncToServer()" 
                    :disabled="!hasUnsavedChanges || isSaving"
                    :class="(!hasUnsavedChanges || isSaving) ? 'opacity-50 cursor-not-allowed bg-gray-200 text-gray-500 border-gray-200' : 'bg-amber-400 hover:bg-amber-500 text-amber-950 border-amber-500 shadow-sm'"
                    class="btn btn-sm rounded-lg border px-5 text-xs font-bold transition-all normal-case flex items-center gap-1.5">
                    <template x-if="isSaving">
                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                    </template>
                    <span x-text="isSaving ? 'Saving...' : 'Save'"></span>
                </button>
            </div>
        </div>

        <!-- Modals -->
        <dialog id="create_template_modal" class="modal">
            <div class="modal-box bg-white rounded-xl border border-gray-200 p-5 max-w-sm">
                <h3 class="font-bold text-base text-gray-900">Create New Stage Template</h3>
                <p class="py-1 text-[11px] text-gray-500">Set up a new reusable delivery template.</p>
                <div class="form-control w-full mt-3">
                    <label class="label py-0.5"><span class="label-text font-medium text-[11px]">Template Name</span></label>
                    <input type="text" x-model="newTemplateName" class="input input-sm input-bordered w-full rounded-lg text-xs" placeholder="e.g., Infrastructure Baseline" />
                </div>
                <div class="modal-action mt-5 flex justify-end gap-2">
                    <button type="button" @click="document.getElementById('create_template_modal').close()" class="btn btn-ghost btn-xs rounded-lg normal-case">Cancel</button>
                    <button type="button" @click="submitCreateTemplate()" :disabled="!newTemplateName" class="btn bg-amber-200 hover:bg-amber-300 text-amber-900 border-amber-300 btn-xs rounded-lg normal-case shadow-none">Create Template</button>
                </div>
            </div>
        </dialog>

        <!-- Apply Template Modal -->
        <dialog id="apply_template_modal" class="modal">
            <div class="modal-box bg-white rounded-xl border border-gray-200 p-4 max-w-sm">
                <h3 class="font-bold text-base text-gray-900">Apply Template to Project</h3>
                <p class="py-1 text-[11px] text-gray-500">Select a project to apply <span class="font-semibold text-amber-900" x-text="activeTemplate ? activeTemplate.name : ''"></span>.</p>

                <form :action="activeTemplate ? `/templates/${activeTemplate.id}/apply` : '#'" method="POST">
                    @csrf
                    <div class="form-control w-full mt-2">
                        <label class="label py-0.5"><span class="label-text font-medium text-[11px]">Target Project</span></label>
                        <select name="project_id" required class="select select-sm select-bordered w-full rounded-lg focus:border-amber-400 text-xs">
                            <option value="">-- Select Project --</option>
                            @if(isset($projects))
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->ref }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="modal-action mt-4 flex gap-2 justify-end">
                        <button type="button" onclick="document.getElementById('apply_template_modal').close()" class="btn btn-ghost btn-xs rounded-lg normal-case">Cancel</button>
                        <button type="submit" class="btn bg-amber-200 hover:bg-amber-300 text-amber-900 border-amber-300 btn-xs rounded-lg normal-case shadow-none">Apply Template</button>
                    </div>
                </form>
            </div>
        </dialog>
        <!-- Custom Confirm Modal -->
        <dialog id="custom_confirm_modal" class="modal">
            <div class="modal-box bg-white rounded-xl border border-gray-200 p-5 max-w-sm">
                <h3 class="font-bold text-base text-gray-900" x-text="confirmConfig.title"></h3>
                <p class="py-2 text-[12px] text-gray-500" x-text="confirmConfig.message"></p>
                <div class="modal-action mt-4 flex justify-end gap-2">
                    <button type="button" @click="document.getElementById('custom_confirm_modal').close()" class="btn btn-ghost btn-xs rounded-lg normal-case text-gray-600">Cancel</button>
                    <button type="button" @click="confirmConfig.onConfirm()" class="btn bg-red-50 hover:bg-red-100 text-red-600 border-red-200 btn-xs rounded-lg normal-case shadow-none px-4">Yes, proceed</button>
                </div>
            </div>
        </dialog>

        <!-- Custom Alert Modal -->
        <dialog id="custom_alert_modal" class="modal">
            <div class="modal-box bg-white rounded-xl border border-gray-200 p-5 max-w-sm">
                <h3 class="font-bold text-base text-red-600 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    <span x-text="alertConfig.title"></span>
                </h3>
                <p class="py-2 text-[12px] text-gray-600" x-text="alertConfig.message"></p>
                <div class="modal-action mt-2">
                    <button type="button" @click="document.getElementById('custom_alert_modal').close()" class="btn bg-gray-100 hover:bg-gray-200 text-gray-700 btn-xs rounded-lg normal-case shadow-none px-4">OK</button>
                </div>
            </div>
        </dialog>

    </div>

    <script>
        function templateBuilder() {
            return {
                templates: @json($templates),
                activeTemplate: null,
                activeTrackId: null,
                
                managingTracks: false,
                editingTemplateName: false,
                editingTrackId: null,
                editingMilestoneId: null,
                
                newTemplateName: '',
                hasUnsavedChanges: false,
                isSaving: false,
                tempIdCounter: 1,

                confirmConfig: { title: '', message: '', onConfirm: null },
                alertConfig: { title: '', message: '' },

                init() {
                    if(this.templates && this.templates.length > 0) {
                        this.activeTemplate = this.templates.find(t => t.is_active) || this.templates[0];
                        if(this.activeTemplate.tracks && this.activeTemplate.tracks.length > 0) {
                            this.activeTrackId = this.activeTemplate.tracks[0].id;
                        }
                    }
                    this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                },

                showConfirm(title, message, callback) {
                    this.confirmConfig.title = title;
                    this.confirmConfig.message = message;
                    this.confirmConfig.onConfirm = () => {
                        document.getElementById('custom_confirm_modal').close();
                        if (callback) callback();
                    };
                    document.getElementById('custom_confirm_modal').showModal();
                },

                showAlert(title, message) {
                    this.alertConfig.title = title;
                    this.alertConfig.message = message;
                    document.getElementById('custom_alert_modal').showModal();
                },

                generateTempId() {
                    return 'temp-' + (this.tempIdCounter++);
                },

                get activeTrack() {
                    if(!this.activeTemplate || !this.activeTemplate.tracks) return null;
                    return this.activeTemplate.tracks.find(t => t.id === this.activeTrackId);
                },

                getPhases(track) {
                    if(!track || !track.milestones) return [];
                    return track.milestones.filter(m => !m.parent_id);
                },

                getChildren(parentId) {
                    if(!this.activeTrack || !this.activeTrack.milestones) return [];
                    return this.activeTrack.milestones.filter(m => m.parent_id === parentId);
                },
                
                selectTemplate(template) {
                    if (this.hasUnsavedChanges) {
                        this.showConfirm(
                            'Unsaved Changes', 
                            'Are you sure you want to switch templates? Your unsaved changes will be lost.',
                            () => this.performTemplateSwitch(template)
                        );
                        return;
                    }
                    this.performTemplateSwitch(template);
                },

                performTemplateSwitch(template) {
                    this.hasUnsavedChanges = false;
                    this.activeTemplate = template;
                    if (template.tracks && template.tracks.length > 0) {
                        this.activeTrackId = template.tracks[0].id;
                    } else {
                        this.activeTrackId = null;
                    }
                    this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                },

                async apiCall(url, method, body = null) {
                    const options = {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    };
                    if (body) options.body = JSON.stringify(body);
                    const res = await fetch(url, options);
                    if (!res.ok) {
                        const errorData = await res.text();
                        console.error('API Error Response:', errorData);
                        const err = new Error('API request failed');
                        try { err.apiResponse = JSON.parse(errorData); } catch(e){}
                        throw err;
                    }
                    return res.json();
                },

                openCreateModal() {
                    this.newTemplateName = '';
                    document.getElementById('create_template_modal').showModal();
                },

                async submitCreateTemplate() {
                    try {
                        const res = await this.apiCall('/templates', 'POST', { name: this.newTemplateName, description: 'New custom template' });
                        this.templates.unshift(res.template);
                        this.selectTemplate(res.template);
                        document.getElementById('create_template_modal').close();
                    } catch (e) {
                        console.error(e);
                        this.showAlert("Error", "Failed to create template.");
                    }
                },

                applyToProject() {
                    if (!this.activeTemplate) return;
                    document.getElementById('apply_template_modal').showModal();
                },

                addPredefinedTrack(name) {
                    this.managingTracks = false;
                    const newTrack = { id: this.generateTempId(), name: name, milestones: [] };
                    if(!this.activeTemplate.tracks) this.activeTemplate.tracks = [];
                    this.activeTemplate.tracks.push(newTrack);
                    this.activeTemplate.tracks_count = (this.activeTemplate.tracks_count || 0) + 1;
                    this.activeTrackId = newTrack.id;
                    this.hasUnsavedChanges = true;
                    this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                },

                addCustomTrack() {
                    this.addPredefinedTrack('New Custom Track');
                },

                editTrack(track) {
                    this.editingTrackId = track.id;
                    this.$nextTick(() => {
                        const el = document.getElementById('track-input-'+track.id);
                        if(el) { el.focus(); el.select(); }
                    });
                },

                saveTrack(track) {
                    this.editingTrackId = null;
                    if(!track.name.trim()) return;
                    this.hasUnsavedChanges = true;
                },

                addPhase() {
                    const newPhase = { id: this.generateTempId(), title: 'New Phase', parent_id: null };
                    if(!this.activeTrack.milestones) this.activeTrack.milestones = [];
                    this.activeTrack.milestones.push(newPhase);
                    this.activeTemplate.milestones_count = (this.activeTemplate.milestones_count || 0) + 1;
                    this.hasUnsavedChanges = true;
                    this.editMilestone(newPhase);
                    this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                },

                addChildMilestone(parentId) {
                    const newChild = { id: this.generateTempId(), title: 'New Milestone', parent_id: parentId };
                    if(!this.activeTrack.milestones) this.activeTrack.milestones = [];
                    this.activeTrack.milestones.push(newChild);
                    this.activeTemplate.milestones_count = (this.activeTemplate.milestones_count || 0) + 1;
                    this.hasUnsavedChanges = true;
                    this.editMilestone(newChild);
                    this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                },

                editMilestone(ms) {
                    this.editingMilestoneId = ms.id;
                    this.$nextTick(() => {
                        const el = document.getElementById('ms-input-'+ms.id);
                        if(el) { el.focus(); el.select(); }
                    });
                },

                saveMilestone(ms) {
                    this.editingMilestoneId = null;
                    if(!ms.title.trim()) return; 
                    this.hasUnsavedChanges = true;
                },

                deleteMilestone(ms) {
                    this.showConfirm(
                        'Delete Milestone',
                        'Are you sure you want to delete this?',
                        () => {
                            try {
                                this.activeTrack.milestones = this.activeTrack.milestones.filter(m => m.id !== ms.id && m.parent_id !== ms.id);
                                this.activeTemplate.milestones_count = Math.max(0, (this.activeTemplate.milestones_count || 1) - 1);
                                this.hasUnsavedChanges = true;
                            } catch (e) { console.error(e); }
                        }
                    );
                },

                async syncToServer() {
                    if (!this.activeTemplate || !this.hasUnsavedChanges) return;
                    this.isSaving = true;
                    try {
                        const payload = { tracks: this.activeTemplate.tracks || [] };
                        const res = await this.apiCall(`/templates/${this.activeTemplate.id}/sync`, 'PUT', payload);
                        
                        // Update local template with DB version (to get real IDs)
                        const index = this.templates.findIndex(t => t.id === res.template.id);
                        if (index !== -1) {
                            this.templates[index] = res.template;
                        }
                        this.activeTemplate = res.template;
                        
                        // Try to keep the active track selected if it still exists
                        if (this.activeTrackId && this.activeTemplate.tracks) {
                            const stillExists = this.activeTemplate.tracks.find(t => t.id === this.activeTrackId || t.name === this.activeTrack.name);
                            if (stillExists) {
                                this.activeTrackId = stillExists.id;
                            } else {
                                this.activeTrackId = this.activeTemplate.tracks.length > 0 ? this.activeTemplate.tracks[0].id : null;
                            }
                        }
                        
                        this.hasUnsavedChanges = false;
                        this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                        
                    } catch(e) {
                        console.error('API Catch Error:', e);
                        let errMsg = "Failed to save changes.";
                        if (e.message !== 'API request failed') {
                            errMsg = e.message;
                        } else if (e.apiResponse) {
                            errMsg = e.apiResponse.message || errMsg;
                        }
                        this.showAlert("Error", errMsg);
                    } finally {
                        this.isSaving = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>

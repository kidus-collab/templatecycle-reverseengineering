<x-app-layout title="Project Stage Templates - Project Cycle">
    
    <div class="space-y-4" x-data="templatesEditor()">
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
            <div class="flex items-center gap-2 self-start sm:self-center">
                <button 
                    @click="applyToProject()" 
                    class="btn bg-amber-100/90 hover:bg-amber-200 text-amber-950 border border-amber-300/80 hover:border-amber-400 rounded-lg px-4 py-1.5 text-xs font-semibold shadow-xs transition-all duration-150 normal-case flex items-center gap-1.5">
                    <span>Apply to project</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-amber-800"></i>
                </button>
            </div>
        </header>

        <!-- Layout Grid: Left Sidebar & Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

            <!-- LEFT COLUMN: Templates List -->
            <aside class="lg:col-span-4 bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-gray-200/80 shadow-xs space-y-3">
                <div>
                    <span class="text-[9px] font-bold tracking-widest text-gray-400 uppercase">TEMPLATES</span>
                    <h2 class="text-sm font-semibold text-gray-900 mt-0.5">Choose a stage template to edit</h2>
                    <p class="text-[11px] text-gray-500 mt-0.5">Used when a project is first created or refreshed from Project details.</p>
                </div>

                <!-- Create New Template Button -->
                <button 
                    @click="openCreateModal()" 
                    class="w-fit px-4 btn btn-outline btn-xs text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400 font-medium normal-case rounded-lg flex items-center justify-center gap-1.5 py-1.5">
                    <i data-lucide="plus" class="w-3.5 h-3.5 text-gray-600"></i>
                    <span>Create new template</span>
                </button>

                <!-- Template Cards List -->
                <div class="space-y-2.5 pt-0.5">
                    <template x-for="template in templates" :key="template.id">
                        <div 
                            @click="selectTemplate(template)"
                            :class="{
                                'bg-amber-50/40 border-amber-300/90 ring-1 ring-amber-400/50 shadow-2xs': activeTemplate.id === template.id,
                                'bg-white border-gray-200 hover:border-gray-300 hover:bg-gray-50/50': activeTemplate.id !== template.id
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
                                <span x-text="(template.tracks ? template.tracks.reduce((acc, t) => acc + (t.milestones ? t.milestones.length : 0), 0) : 0) + 'P'"></span>
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
            <main class="lg:col-span-8 space-y-4">

                <!-- Top Metrics Bar Components -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <x-metric-card label="TRACKS" x-text="activeTemplate ? activeTemplate.tracks_count : 0" subtext="0 active stages" />
                    <x-metric-card label="PHASES" x-text="activeTemplate && activeTemplate.tracks ? activeTemplate.tracks.reduce((acc, t) => acc + (t.milestones ? t.milestones.length : 0), 0) : 0" subtext="Ordered stage sequence" />
                    <x-metric-card label="MILESTONES" x-text="activeTemplate ? activeTemplate.milestones_count : 0" subtext="Major checkpoints" />
                    <x-metric-card label="SUB-MILESTONES" x-text="activeTemplate ? activeTemplate.sub_milestones_count : 0" subtext="Detailed stage checks" />
                </div>

                <!-- Tracks & Phases Main Editor Box -->
                <div class="bg-white rounded-xl border border-gray-200/90 shadow-xs p-4 md:p-5 min-h-[500px]">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">

                        <!-- Inner Left: Track Selector -->
                        <div class="md:col-span-4 space-y-3 border-b md:border-b-0 md:border-r border-gray-100 pb-3 md:pb-0 md:pr-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">STAGE TRACKS</span>
                                <span class="text-[9px] font-medium text-amber-800 bg-amber-100/70 px-1.5 py-0.2 rounded border border-amber-200" title="Rule restriction">1 track / project</span>
                            </div>

                            <div class="space-y-1.5">
                                <template x-if="activeTemplate">
                                <template x-for="track in activeTemplate.tracks" :key="track.id">
                                    <button 
                                        @click="activeTrackId = track.id"
                                        :class="{
                                            'bg-amber-100/90 text-gray-900 font-semibold border-amber-300 shadow-2xs': activeTrackId === track.id,
                                            'bg-gray-50 hover:bg-gray-100 text-gray-700 border-transparent': activeTrackId !== track.id
                                        }"
                                        class="w-full flex items-center justify-between px-3 py-2 rounded-lg border text-xs transition-all duration-150 text-left">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <input type="radio" name="active_track_radio" :checked="activeTrackId === track.id" class="radio radio-xs radio-warning" />
                                            <span x-text="track.name" class="truncate pr-1"></span>
                                        </div>
                                        <span 
                                            :class="activeTrackId === track.id ? 'bg-amber-900/10 text-amber-900' : 'bg-gray-200/80 text-gray-600'"
                                            class="text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shrink-0" 
                                            x-text="track.milestones ? track.milestones.length : 0"></span>
                                    </button>
                                </template>
                                </template>
                            </div>

                            <button 
                                @click="$root.showNotification('Track management mode opened.')" 
                                class="w-full p-2 rounded-lg border border-dashed border-gray-300 hover:border-amber-400 hover:bg-amber-50/30 text-[11px] font-medium text-gray-600 hover:text-amber-900 transition-all duration-150 flex items-center justify-center gap-1">
                                <i data-lucide="plus" class="w-3 h-3"></i>
                                <span>Manage tracks</span>
                            </button>
                        </div>

                        <!-- Inner Right: Phase Items Sequence -->
                        <div class="md:col-span-8 space-y-2.5">
                            <div class="space-y-2">
                                <template x-for="(phase, index) in currentPhases" :key="phase.id">
                                    <div class="flex items-center justify-between p-2.5 bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-150 group">
                                        <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                            <div class="drag-handle text-gray-300 group-hover:text-gray-500 transition-colors p-0.5" title="Reorder">
                                                <i data-lucide="grip-vertical" class="w-3.5 h-3.5"></i>
                                            </div>
                                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                            <span class="text-xs font-semibold text-gray-900 truncate" x-text="phase.title"></span>
                                        </div>

                                        <div class="flex items-center gap-2 pl-2 shrink-0">
                                            <button 
                                                @click="phase.starred = !phase.starred; $root.refreshIcons()" 
                                                class="text-gray-300 hover:text-amber-500 transition-colors p-0.5" 
                                                :class="{'text-amber-400': phase.starred}">
                                                <i data-lucide="star" class="w-3.5 h-3.5" :class="{'fill-amber-400': phase.starred}"></i>
                                            </button>

                                            <button 
                                                @click="deletePhase(index)" 
                                                class="text-gray-300 hover:text-red-500 transition-colors p-0.5">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="pt-1">
                                <button 
                                    @click="openAddPhaseModal()" 
                                    class="w-full py-2 px-3 rounded-lg border border-dashed border-gray-300 hover:border-amber-400 hover:bg-amber-50/40 text-xs font-medium text-gray-600 hover:text-amber-900 transition-all duration-150 flex items-center justify-center gap-1.5">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>Add phase</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>

        <!-- Modals -->
        <dialog id="add_phase_modal" class="modal">
            <div class="modal-box bg-white rounded-xl border border-gray-200 p-4 max-w-sm">
                <h3 class="font-bold text-base text-gray-900">Add New Stage Phase</h3>
                <p class="py-1 text-[11px] text-gray-500">Enter a title for the new phase in this track sequence.</p>
                <div class="form-control w-full mt-2">
                    <label class="label py-0.5"><span class="label-text font-medium text-[11px]">Phase Name</span></label>
                    <input type="text" x-model="newPhaseName" placeholder="e.g., Quality Assurance Checks" class="input input-sm input-bordered w-full rounded-lg focus:border-amber-400 text-xs" @keyup.enter="addPhase()" />
                </div>
                <div class="modal-action mt-4">
                    <form method="dialog" class="flex gap-2">
                        <button class="btn btn-ghost btn-xs rounded-lg normal-case">Cancel</button>
                        <button type="button" @click="addPhase()" class="btn bg-amber-200 hover:bg-amber-300 text-amber-900 border-amber-300 btn-xs rounded-lg normal-case">Add Phase</button>
                    </form>
                </div>
            </div>
        </dialog>

        <dialog id="create_template_modal" class="modal">
            <div class="modal-box bg-white rounded-xl border border-gray-200 p-4 max-w-sm">
                <h3 class="font-bold text-base text-gray-900">Create New Stage Template</h3>
                <p class="py-1 text-[11px] text-gray-500">Set up a new reusable delivery template.</p>
                <div class="space-y-2.5 mt-2">
                    <div class="form-control w-full">
                        <label class="label py-0.5"><span class="label-text font-medium text-[11px]">Template Name</span></label>
                        <input type="text" x-model="newTemplate.name" placeholder="e.g., Infrastructure Baseline" class="input input-sm input-bordered w-full rounded-lg focus:border-amber-400 text-xs" />
                    </div>
                    <div class="form-control w-full">
                        <label class="label py-0.5"><span class="label-text font-medium text-[11px]">Description</span></label>
                        <textarea x-model="newTemplate.description" placeholder="Brief summary of template purpose" class="textarea textarea-bordered w-full rounded-lg focus:border-amber-400 text-xs h-16"></textarea>
                    </div>
                </div>
                <div class="modal-action mt-4">
                    <form method="dialog" class="flex gap-2">
                        <button class="btn btn-ghost btn-xs rounded-lg normal-case">Cancel</button>
                        <button type="button" @click="createTemplate()" class="btn bg-amber-200 hover:bg-amber-300 text-amber-900 border-amber-300 btn-xs rounded-lg normal-case">Create Template</button>
                    </form>
                </div>
            </div>
        </dialog>

    </div>

    <script>
        function templatesEditor() {
            return {
                templates: @json($templates),
                activeTemplate: null,
                activeTrackId: 'q-const',
                newPhaseName: '',
                newTemplate: { name: '', description: '' },

                init() {
                    this.activeTemplate = this.templates.find(t => t.id === 'quick-tpl') || this.templates[0];
                    if (this.activeTemplate.tracks.length > 0) {
                        this.activeTrackId = this.activeTemplate.tracks[0].id;
                    }
                    this.$nextTick(() => { if (window.lucide) window.lucide.createIcons(); });
                },

                get currentPhases() {
                    if (!this.activeTemplate || !this.activeTemplate.tracks) return [];
                    const track = this.activeTemplate.tracks.find(t => t.id === this.activeTrackId);
                    return track ? (track.milestones || []) : [];
                },

                selectTemplate(template) {
                    this.activeTemplate = template;
                    if (template.tracks && template.tracks.length > 0) {
                        this.activeTrackId = template.tracks[0].id;
                    }
                    this.$root.refreshIcons();
                },

                deletePhase(index) {
                    const track = this.activeTemplate.tracks.find(t => t.id === this.activeTrackId);
                    if (track && track.milestones) {
                        track.milestones.splice(index, 1);
                    }
                    this.$root.refreshIcons();
                    this.$root.showNotification('Phase removed from sequence.');
                },

                openAddPhaseModal() {
                    this.newPhaseName = '';
                    document.getElementById('add_phase_modal').showModal();
                },

                addPhase() {
                    if (!this.newPhaseName.trim()) return;
                    const track = this.activeTemplate.tracks.find(t => t.id === this.activeTrackId);
                    if (track) {
                        if (!track.milestones) track.milestones = [];
                        track.milestones.push({ id: Date.now(), title: this.newPhaseName.trim(), starred: false });
                    }
                    document.getElementById('add_phase_modal').close();
                    this.$root.showNotification(`Phase '${this.newPhaseName}' added successfully.`);
                    this.newPhaseName = '';
                    this.$root.refreshIcons();
                },

                openCreateModal() {
                    this.newTemplate = { name: '', description: '' };
                    document.getElementById('create_template_modal').showModal();
                },

                createTemplate() {
                    if (!this.newTemplate.name.trim()) return;
                    const newTpl = {
                        id: 'tpl-' + Date.now(),
                        name: this.newTemplate.name.trim(),
                        description: this.newTemplate.description.trim() || 'Custom template stage workflow',
                        tracks_count: 1, milestones_count: 0, sub_milestones_count: 0, is_active: false,
                        tracks: [{ id: 'tr-' + Date.now(), name: 'Primary Stage', count: 1, milestones: [{ id: Date.now(), title: 'Initial Kickoff Phase', starred: false }] }]
                    };
                    this.templates.unshift(newTpl);
                    this.selectTemplate(newTpl);
                    document.getElementById('create_template_modal').close();
                    this.$root.showNotification(`Template '${newTpl.name}' created!`);
                },

                applyToProject() {
                    this.$root.showNotification(`Template track '${this.activeTemplate.name}' applied to project (1 track limit enforced).`);
                }
            }
        }
    </script>
</x-app-layout>

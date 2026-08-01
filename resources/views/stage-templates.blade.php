<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" class="h-full bg-[#FAF9F6]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Stage Templates - Project Management</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind & DaisyUI via CDN for instant high-fidelity styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
    
    <!-- Alpine.js for Interactive UI state -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        amber: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FAF9F6;
            color: #1F2937;
        }
        .drag-handle {
            cursor: grab;
        }
        .drag-handle:active {
            cursor: grabbing;
        }
    </style>
</head>
<body class="min-h-screen antialiased bg-[#FAF9F6] text-gray-800 p-4 md:p-8" x-data="templateApp()">

    <!-- Main Container -->
    <div class="max-w-[1550px] mx-auto space-y-6">

        <!-- Top Header Navigation & Action -->
        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumb -->
                <div class="text-[11px] font-semibold tracking-wider text-amber-700/70 uppercase mb-1 flex items-center gap-1.5">
                    <span>PROJECT MANAGEMENT</span>
                    <span>•</span>
                    <span>PROJECT STAGE TEMPLATES</span>
                </div>
                <!-- Title -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Project Stage Templates</h1>
                <!-- Subtitle -->
                <p class="text-sm text-gray-500 mt-1 max-w-3xl">
                    Set up the reusable delivery stages once, then copy that plan into each new project as a starting point.
                </p>
            </div>

            <!-- Top Right Action Button - Explicitly restricted to Apply to project button as requested -->
            <div class="flex items-center gap-3 self-start sm:self-center">
                <button 
                    @click="applyToProject()" 
                    class="btn bg-amber-100/90 hover:bg-amber-200 text-amber-900 border border-amber-300/80 hover:border-amber-400 rounded-lg px-5 py-2 text-sm font-semibold shadow-sm transition-all duration-150 normal-case flex items-center gap-2">
                    <span>Apply to project</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-amber-800"></i>
                </button>
            </div>
        </header>

        <!-- Notification Toast -->
        <div x-show="toast.show" x-transition 
             class="fixed top-5 right-5 z-50 alert alert-success shadow-lg text-white font-medium max-w-md"
             style="display: none;">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            <span x-text="toast.message"></span>
        </div>

        <!-- Layout Grid: Left Sidebar & Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT SIDEBAR: Templates List -->
            <aside class="lg:col-span-4 bg-white/70 backdrop-blur-sm p-5 rounded-2xl border border-gray-200/80 shadow-xs space-y-4">
                
                <div>
                    <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">TEMPLATES</span>
                    <h2 class="text-base font-semibold text-gray-900 mt-0.5">Choose a stage template to edit</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Used when a project is first created or refreshed from Project details.</p>
                </div>

                <!-- Create New Template Button -->
                <button 
                    @click="openCreateModal()" 
                    class="w-full btn btn-outline btn-sm text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400 font-medium normal-case rounded-lg flex items-center justify-center gap-2 py-2">
                    <i data-lucide="plus" class="w-4 h-4 text-gray-600"></i>
                    <span>Create new template</span>
                </button>

                <!-- Template Cards List -->
                <div class="space-y-3 pt-1">
                    <template x-for="template in templates" :key="template.id">
                        <div 
                            @click="selectTemplate(template)"
                            :class="{
                                'bg-amber-50/40 border-amber-300/90 ring-1 ring-amber-400/50 shadow-sm': activeTemplate.id === template.id,
                                'bg-white border-gray-200 hover:border-gray-300 hover:bg-gray-50/50': activeTemplate.id !== template.id
                            }"
                            class="p-4 rounded-xl border transition-all duration-200 cursor-pointer relative group">
                            
                            <!-- Active Badge -->
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="font-semibold text-sm text-gray-900 group-hover:text-amber-900 transition-colors" x-text="template.name"></h3>
                                <span x-show="template.isActive" class="text-[10px] font-semibold text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-full uppercase tracking-wider">Active</span>
                            </div>

                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed" x-text="template.description"></p>

                            <!-- Meta Stats -->
                            <div class="mt-3 text-[11px] font-medium text-gray-500 flex items-center gap-1.5 font-mono">
                                <span x-text="template.tracksCount + 'T'"></span>
                                <span>·</span>
                                <span x-text="template.phasesCount + 'P'"></span>
                                <span>·</span>
                                <span x-text="template.milestonesCount + 'M'"></span>
                                <span>·</span>
                                <span x-text="template.subMilestonesCount + 'SM'"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </aside>

            <!-- RIGHT MAIN PANEL: Metrics & Tracks/Phases Editor -->
            <main class="lg:col-span-8 space-y-6">

                <!-- Top Metrics Bar (4 Cards) -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    
                    <!-- Metric 1: Tracks -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200/90 shadow-2xs">
                        <div class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">TRACKS</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1" x-text="activeTemplate.tracksCount">3</div>
                        <div class="text-xs text-gray-500 mt-0.5">0 active stages</div>
                    </div>

                    <!-- Metric 2: Phases -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200/90 shadow-2xs">
                        <div class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">PHASES</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1" x-text="activeTemplate.phasesCount">16</div>
                        <div class="text-xs text-gray-500 mt-0.5">Ordered stage sequence</div>
                    </div>

                    <!-- Metric 3: Milestones -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200/90 shadow-2xs">
                        <div class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">MILESTONES</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1" x-text="activeTemplate.milestonesCount">128</div>
                        <div class="text-xs text-gray-500 mt-0.5">Major checkpoints</div>
                    </div>

                    <!-- Metric 4: Sub-Milestones -->
                    <div class="bg-white p-4 rounded-xl border border-gray-200/90 shadow-2xs">
                        <div class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">SUB-MILESTONES</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1" x-text="activeTemplate.subMilestonesCount">723</div>
                        <div class="text-xs text-gray-500 mt-0.5">Detailed stage checks</div>
                    </div>
                </div>

                <!-- Tracks & Phases Main Editor Box -->
                <div class="bg-white rounded-2xl border border-gray-200/90 shadow-xs p-5 md:p-6 min-h-[550px]">
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

                        <!-- Inner Left: Track Pills Selector -->
                        <div class="md:col-span-4 space-y-4 border-b md:border-b-0 md:border-r border-gray-100 pb-4 md:pb-0 md:pr-4">
                            
                            <div class="space-y-2">
                                <template x-for="track in activeTemplate.tracks" :key="track.id">
                                    <button 
                                        @click="activeTrackId = track.id"
                                        :class="{
                                            'bg-amber-100/90 text-gray-900 font-semibold border-amber-300 shadow-2xs': activeTrackId === track.id,
                                            'bg-gray-50 hover:bg-gray-100 text-gray-700 border-transparent': activeTrackId !== track.id
                                        }"
                                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl border text-sm transition-all duration-150 text-left">
                                        <span x-text="track.name" class="truncate pr-2"></span>
                                        <span 
                                            :class="activeTrackId === track.id ? 'bg-amber-900/10 text-amber-900' : 'bg-gray-200/80 text-gray-600'"
                                            class="text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center shrink-0" 
                                            x-text="track.count"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- Manage Tracks Action Button -->
                            <button 
                                @click="manageTracks()" 
                                class="w-full p-2.5 rounded-xl border border-dashed border-gray-300 hover:border-amber-400 hover:bg-amber-50/30 text-xs font-medium text-gray-600 hover:text-amber-900 transition-all duration-150 flex items-center justify-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Manage tracks</span>
                            </button>
                        </div>

                        <!-- Inner Right: Phase Items Sequence -->
                        <div class="md:col-span-8 space-y-3">
                            
                            <!-- Phase Items List -->
                            <div class="space-y-2.5">
                                <template x-for="(phase, index) in currentPhases" :key="phase.id">
                                    <div class="flex items-center justify-between p-3.5 bg-white border border-gray-200 rounded-xl hover:border-gray-300 hover:shadow-2xs transition-all duration-150 group">
                                        
                                        <!-- Left Handle & Title -->
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            <!-- Drag Handle Icon -->
                                            <div class="drag-handle text-gray-300 group-hover:text-gray-500 transition-colors p-1" title="Reorder">
                                                <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                                            </div>

                                            <!-- Chevron Icon -->
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 shrink-0"></i>

                                            <!-- Phase Name -->
                                            <span class="text-sm font-semibold text-gray-900 truncate" x-text="phase.name"></span>
                                        </div>

                                        <!-- Right Action Icons -->
                                        <div class="flex items-center gap-3 pl-2">
                                            <!-- Favorite Star Icon -->
                                            <button 
                                                @click="toggleStar(phase)" 
                                                class="text-gray-300 hover:text-amber-500 transition-colors p-1" 
                                                :class="{'text-amber-400': phase.starred}"
                                                title="Star item">
                                                <i data-lucide="star" class="w-4 h-4" :class="{'fill-amber-400': phase.starred}"></i>
                                            </button>

                                            <!-- Delete Trash Icon -->
                                            <button 
                                                @click="deletePhase(index)" 
                                                class="text-gray-300 hover:text-red-500 transition-colors p-1" 
                                                title="Delete phase">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Add Phase Action Button -->
                            <div class="pt-2">
                                <button 
                                    @click="openAddPhaseModal()" 
                                    class="w-full py-3 px-4 rounded-xl border border-dashed border-gray-300 hover:border-amber-400 hover:bg-amber-50/40 text-sm font-medium text-gray-600 hover:text-amber-900 transition-all duration-150 flex items-center justify-center gap-2">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    <span>Add phase</span>
                                </button>
                            </div>

                        </div>

                    </div>

                </div>

            </main>

        </div>

        <!-- Add Phase Modal -->
        <dialog id="add_phase_modal" class="modal">
            <div class="modal-box bg-white rounded-2xl border border-gray-200">
                <h3 class="font-bold text-lg text-gray-900">Add New Stage Phase</h3>
                <p class="py-2 text-xs text-gray-500">Enter a title for the new phase to be included in this track sequence.</p>
                <div class="form-control w-full mt-3">
                    <label class="label"><span class="label-text font-medium text-xs">Phase Name</span></label>
                    <input type="text" x-model="newPhaseName" placeholder="e.g., Quality Assurance Checks" class="input input-bordered w-full rounded-xl focus:border-amber-400 text-sm" @keyup.enter="addPhase()" />
                </div>
                <div class="modal-action">
                    <form method="dialog" class="flex gap-2">
                        <button class="btn btn-ghost btn-sm rounded-lg normal-case">Cancel</button>
                        <button type="button" @click="addPhase()" class="btn bg-amber-200 hover:bg-amber-300 text-amber-900 border-amber-300 btn-sm rounded-lg normal-case">Add Phase</button>
                    </form>
                </div>
            </div>
        </dialog>

        <!-- Create Template Modal -->
        <dialog id="create_template_modal" class="modal">
            <div class="modal-box bg-white rounded-2xl border border-gray-200">
                <h3 class="font-bold text-lg text-gray-900">Create New Stage Template</h3>
                <p class="py-2 text-xs text-gray-500">Set up a new reusable delivery template for your project workflows.</p>
                <div class="space-y-3 mt-3">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-medium text-xs">Template Name</span></label>
                        <input type="text" x-model="newTemplate.name" placeholder="e.g., Infrastructure Baseline" class="input input-bordered w-full rounded-xl focus:border-amber-400 text-sm" />
                    </div>
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-medium text-xs">Description</span></label>
                        <textarea x-model="newTemplate.description" placeholder="Brief summary of template purpose" class="textarea textarea-bordered w-full rounded-xl focus:border-amber-400 text-sm h-20"></textarea>
                    </div>
                </div>
                <div class="modal-action">
                    <form method="dialog" class="flex gap-2">
                        <button class="btn btn-ghost btn-sm rounded-lg normal-case">Cancel</button>
                        <button type="button" @click="createTemplate()" class="btn bg-amber-200 hover:bg-amber-300 text-amber-900 border-amber-300 btn-sm rounded-lg normal-case">Create Template</button>
                    </form>
                </div>
            </div>
        </dialog>

    </div>

    <!-- Alpine App State Logic -->
    <script>
        function templateApp() {
            return {
                toast: {
                    show: false,
                    message: ''
                },
                newPhaseName: '',
                newTemplate: {
                    name: '',
                    description: ''
                },
                templates: [
                    {
                        id: 'const-tpl',
                        name: 'Construction template',
                        description: 'Construction',
                        tracksCount: 8,
                        phasesCount: 63,
                        milestonesCount: 364,
                        subMilestonesCount: 0,
                        isActive: false,
                        tracks: [
                            { id: 'c1', name: 'Civil Works', count: 12 },
                            { id: 'c2', name: 'Structural', count: 18 }
                        ]
                    },
                    {
                        id: 'design-tpl',
                        name: 'Design Template',
                        description: 'Template used for design projects',
                        tracksCount: 3,
                        phasesCount: 22,
                        milestonesCount: 221,
                        subMilestonesCount: 0,
                        isActive: false,
                        tracks: [
                            { id: 'd1', name: 'Architectural', count: 8 },
                            { id: 'd2', name: 'MEP Engineering', count: 14 }
                        ]
                    },
                    {
                        id: 'integ-tpl',
                        name: 'Integrated Delivery Baseline',
                        description: 'Design-through-handover template for full-spectrum Mers delivery programs.',
                        tracksCount: 3,
                        phasesCount: 7,
                        milestonesCount: 7,
                        subMilestonesCount: 10,
                        isActive: false,
                        tracks: [
                            { id: 'i1', name: 'Program Setup', count: 3 },
                            { id: 'i2', name: 'Handover Track', count: 4 }
                        ]
                    },
                    {
                        id: 'quick-tpl',
                        name: 'Quick Template',
                        description: 'Consists of 3 Tracks : Construction | Design | Supervision',
                        tracksCount: 3,
                        phasesCount: 16,
                        milestonesCount: 128,
                        subMilestonesCount: 723,
                        isActive: true,
                        tracks: [
                            { 
                                id: 'q-const', 
                                name: 'Construction', 
                                count: 8,
                                phases: [
                                    { id: 1, name: 'Pre-construction', starred: false },
                                    { id: 2, name: 'Sub structure (B1)', starred: false },
                                    { id: 3, name: 'Super structure (GF)', starred: false },
                                    { id: 4, name: 'Interior Construction', starred: false },
                                    { id: 5, name: 'Exterior Finishing Works', starred: false },
                                    { id: 6, name: 'Exterior construction', starred: false },
                                    { id: 7, name: 'Post Construction', starred: false },
                                    { id: 8, name: 'Cost Tracking', starred: false }
                                ]
                            },
                            { 
                                id: 'q-design', 
                                name: 'Design', 
                                count: 3,
                                phases: [
                                    { id: 9, name: 'Conceptual Sketching', starred: false },
                                    { id: 10, name: 'Detailed Blueprinting', starred: true },
                                    { id: 11, name: 'Client Approval Sign-off', starred: false }
                                ]
                            },
                            { 
                                id: 'q-supervision', 
                                name: 'Supervision...', 
                                count: 5,
                                phases: [
                                    { id: 12, name: 'Site Inspection Stage 1', starred: false },
                                    { id: 13, name: 'Safety Audit Phase', starred: false },
                                    { id: 14, name: 'Compliance Review', starred: false },
                                    { id: 15, name: 'Material Testing Verification', starred: false },
                                    { id: 16, name: 'Final Commissioning Audit', starred: false }
                                ]
                            }
                        ]
                    },
                    {
                        id: 'sup-rec-tpl',
                        name: 'Supervision Recovery Sprint',
                        description: 'Focused supervision and construction template for late-stage recovery programs.',
                        tracksCount: 2,
                        phasesCount: 3,
                        milestonesCount: 3,
                        subMilestonesCount: 4,
                        isActive: false,
                        tracks: [
                            { id: 'sr1', name: 'Fast-Track Remediation', count: 2 },
                            { id: 'sr2', name: 'Sprint Signoff', count: 1 }
                        ]
                    },
                    {
                        id: 'sup-tpl',
                        name: 'Supervision Template',
                        description: 'supervision project template',
                        tracksCount: 1,
                        phasesCount: 4,
                        milestonesCount: 12,
                        subMilestonesCount: 18,
                        isActive: false,
                        tracks: [
                            { id: 'st1', name: 'General Supervision', count: 4 }
                        ]
                    }
                ],
                activeTemplate: null,
                activeTrackId: 'q-const',

                init() {
                    // Default to Quick Template
                    this.activeTemplate = this.templates.find(t => t.id === 'quick-tpl') || this.templates[0];
                    if (this.activeTemplate.tracks.length > 0) {
                        this.activeTrackId = this.activeTemplate.tracks[0].id;
                    }
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                },

                get currentPhases() {
                    if (!this.activeTemplate || !this.activeTemplate.tracks) return [];
                    const track = this.activeTemplate.tracks.find(t => t.id === this.activeTrackId);
                    return track ? (track.phases || []) : [];
                },

                selectTemplate(template) {
                    this.activeTemplate = template;
                    if (template.tracks && template.tracks.length > 0) {
                        this.activeTrackId = template.tracks[0].id;
                    }
                    this.refreshIcons();
                },

                toggleStar(phase) {
                    phase.starred = !phase.starred;
                    this.refreshIcons();
                },

                deletePhase(index) {
                    const track = this.activeTemplate.tracks.find(t => t.id === this.activeTrackId);
                    if (track && track.phases) {
                        track.phases.splice(index, 1);
                        track.count = track.phases.length;
                    }
                    this.refreshIcons();
                    this.showNotification('Phase removed from sequence.');
                },

                openAddPhaseModal() {
                    this.newPhaseName = '';
                    document.getElementById('add_phase_modal').showModal();
                },

                addPhase() {
                    if (!this.newPhaseName.trim()) return;
                    const track = this.activeTemplate.tracks.find(t => t.id === this.activeTrackId);
                    if (track) {
                        if (!track.phases) track.phases = [];
                        track.phases.push({
                            id: Date.now(),
                            name: this.newPhaseName.trim(),
                            starred: false
                        });
                        track.count = track.phases.length;
                        this.activeTemplate.phasesCount++;
                    }
                    document.getElementById('add_phase_modal').close();
                    this.showNotification(`Phase '${this.newPhaseName}' added successfully.`);
                    this.newPhaseName = '';
                    this.refreshIcons();
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
                        tracksCount: 1,
                        phasesCount: 1,
                        milestonesCount: 0,
                        subMilestonesCount: 0,
                        isActive: false,
                        tracks: [
                            {
                                id: 'tr-' + Date.now(),
                                name: 'Primary Stage',
                                count: 1,
                                phases: [
                                    { id: Date.now(), name: 'Initial Kickoff Phase', starred: false }
                                ]
                            }
                        ]
                    };
                    this.templates.unshift(newTpl);
                    this.selectTemplate(newTpl);
                    document.getElementById('create_template_modal').close();
                    this.showNotification(`Template '${newTpl.name}' created!`);
                },

                manageTracks() {
                    this.showNotification('Track management mode opened.');
                },

                applyToProject() {
                    this.showNotification(`Template '${this.activeTemplate.name}' applied to project!`);
                },

                showNotification(msg) {
                    this.toast.message = msg;
                    this.toast.show = true;
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                },

                refreshIcons() {
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                }
            }
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" class="h-full bg-[#FAF9F6]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Cycle - Projects & Portfolio</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind & DaisyUI via CDN -->
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
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                            950: '#451a03'
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
            font-size: 13px;
        }
        .drag-handle {
            cursor: grab;
        }
        .drag-handle:active {
            cursor: grabbing;
        }
        .resizer-handle {
            cursor: col-resize;
            user-select: none;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen antialiased bg-[#FAF9F6] text-gray-800 flex flex-col md:flex-row select-none" x-data="mainApp()" :class="{'cursor-col-resize': isResizing}">

    <!-- MOBILE HEADER & MENU TOGGLE BAR -->
    <div class="md:hidden bg-white border-b border-gray-200 p-3 flex items-center justify-between shadow-2xs z-30 sticky top-0">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-amber-400 flex items-center justify-center font-bold text-amber-950 shadow-xs">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <div>
                <h2 class="font-bold text-sm text-gray-900 leading-tight">Project Cycle</h2>
                <span class="text-[9px] font-semibold text-gray-400 tracking-wider uppercase">Stage Delivery v2</span>
            </div>
        </div>
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="btn btn-ghost btn-xs btn-square text-gray-600">
            <i data-lucide="menu" class="w-4 h-4" x-show="!mobileMenuOpen"></i>
            <i data-lucide="x" class="w-4 h-4" x-show="mobileMenuOpen" style="display:none;"></i>
        </button>
    </div>

    <!-- MAIN APP SIDEBAR: Compact Resizable Navigation -->
    <aside 
        :style="window.innerWidth >= 768 ? 'width: ' + sidebarWidth + 'px' : ''"
        :class="mobileMenuOpen ? 'block' : 'hidden md:flex'"
        class="bg-white border-r border-gray-200/90 shrink-0 flex-col p-3 md:min-h-screen shadow-2xs relative z-20 transition-none">
        
        <!-- RESIZE HANDLE (DESKTOP) -->
        <div 
            @mousedown="startResize($event)" 
            class="hidden md:block absolute top-0 right-0 bottom-0 w-2 resizer-handle group hover:bg-amber-400/60 active:bg-amber-500 transition-colors z-30"
            title="Drag to resize sidebar">
            <div class="w-1 h-6 bg-gray-300 group-hover:bg-amber-600 rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 transition-colors"></div>
        </div>

        <div class="space-y-4">
            <!-- App Branding Header (Desktop) -->
            <div class="hidden md:flex items-center gap-2.5 px-2 pt-1">
                <div class="w-7 h-7 rounded-lg bg-amber-400 flex items-center justify-center font-bold text-amber-950 shadow-xs">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
                <div>
                    <h2 class="font-bold text-sm text-gray-900 leading-tight">Project Cycle</h2>
                    <span class="text-[9px] font-semibold text-gray-400 tracking-wider uppercase">Stage Delivery v2</span>
                </div>
            </div>

            <!-- Primary Navigation Links -->
            <nav class="space-y-1">
                <div class="text-[9px] font-bold tracking-widest text-gray-400 uppercase px-2 mb-1.5">NAVIGATION</div>
                
                <!-- Projects / Templates Sidebar Option -->
                <a 
                    href="{{ route('templates') }}"
                    @click.prevent="currentView = 'templates'; mobileMenuOpen = false; refreshIcons(); window.history.pushState({}, '', '{{ route('templates') }}')" 
                    :class="currentView === 'templates' ? 'bg-amber-100/80 text-amber-950 font-semibold border-amber-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 border-transparent'"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg border text-xs transition-all duration-150 text-left group">
                    <div class="flex items-center gap-2 min-w-0">
                        <i data-lucide="folder-kanban" class="w-3.5 h-3.5 shrink-0" :class="currentView === 'templates' ? 'text-amber-800' : 'text-gray-400 group-hover:text-gray-600'"></i>
                        <span class="truncate">Projects</span>
                    </div>
                    <span :class="currentView === 'templates' ? 'bg-amber-900/10 text-amber-900' : 'bg-gray-100 text-gray-500'" class="text-[10px] font-bold px-1.5 py-0.2 rounded-full shrink-0">6</span>
                </a>

                <!-- Project Portfolio Option -->
                <a 
                    href="{{ route('projects.index') }}"
                    @click.prevent="currentView = 'projects'; mobileMenuOpen = false; refreshIcons(); window.history.pushState({}, '', '{{ route('projects.index') }}')" 
                    :class="currentView === 'projects' ? 'bg-amber-100/80 text-amber-950 font-semibold border-amber-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 border-transparent'"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg border text-xs transition-all duration-150 text-left group">
                    <div class="flex items-center gap-2 min-w-0">
                        <i data-lucide="layout-grid" class="w-3.5 h-3.5 shrink-0" :class="currentView === 'projects' ? 'text-amber-800' : 'text-gray-400 group-hover:text-gray-600'"></i>
                        <span class="truncate">Project Portfolio</span>
                    </div>
                    <span :class="currentView === 'projects' ? 'bg-amber-900/10 text-amber-900' : 'bg-gray-100 text-gray-500'" class="text-[10px] font-bold px-1.5 py-0.2 rounded-full shrink-0">6</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- CONTENT WRAPPER -->
    <div class="flex-1 p-3 md:p-6 max-w-[1600px] w-full mx-auto overflow-x-hidden">

        <!-- Notification Toast -->
        <div x-show="toast.show" x-transition 
             class="fixed top-4 right-4 z-50 alert alert-success shadow-md text-white text-xs font-medium max-w-sm py-2 px-3"
             style="display: none;">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            <span x-text="toast.message"></span>
        </div>

        <!-- ================= VIEW 1: PROJECTS (STAGE TEMPLATES) ================= -->
        <div x-show="currentView === 'templates'" x-cloak class="space-y-4">

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

                <!-- Top Right Action Button - Only Apply to project button -->
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
                        class="w-full btn btn-outline btn-xs text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400 font-medium normal-case rounded-lg flex items-center justify-center gap-1.5 py-1.5">
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
                                
                                <!-- Active Badge -->
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-semibold text-xs text-gray-900 group-hover:text-amber-900 transition-colors" x-text="template.name"></h3>
                                    <span x-show="template.isActive" class="text-[9px] font-semibold text-amber-700 bg-amber-100/80 px-1.5 py-0.2 rounded-full uppercase tracking-wider">Active</span>
                                </div>

                                <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2 leading-relaxed" x-text="template.description"></p>

                                <!-- Meta Stats -->
                                <div class="mt-2 text-[10px] font-medium text-gray-500 flex items-center gap-1 font-mono">
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
                <main class="lg:col-span-8 space-y-4">

                    <!-- Top Metrics Bar -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        
                        <!-- Metric 1: Tracks -->
                        <div class="bg-white p-3 rounded-xl border border-gray-200/90 shadow-2xs">
                            <div class="text-[9px] font-bold tracking-widest text-gray-400 uppercase">TRACKS</div>
                            <div class="text-xl font-bold text-gray-900 mt-0.5" x-text="activeTemplate.tracksCount">3</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">0 active stages</div>
                        </div>

                        <!-- Metric 2: Phases -->
                        <div class="bg-white p-3 rounded-xl border border-gray-200/90 shadow-2xs">
                            <div class="text-[9px] font-bold tracking-widest text-gray-400 uppercase">PHASES</div>
                            <div class="text-xl font-bold text-gray-900 mt-0.5" x-text="activeTemplate.phasesCount">16</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">Ordered stage sequence</div>
                        </div>

                        <!-- Metric 3: Milestones -->
                        <div class="bg-white p-3 rounded-xl border border-gray-200/90 shadow-2xs">
                            <div class="text-[9px] font-bold tracking-widest text-gray-400 uppercase">MILESTONES</div>
                            <div class="text-xl font-bold text-gray-900 mt-0.5" x-text="activeTemplate.milestonesCount">128</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">Major checkpoints</div>
                        </div>

                        <!-- Metric 4: Sub-Milestones -->
                        <div class="bg-white p-3 rounded-xl border border-gray-200/90 shadow-2xs">
                            <div class="text-[9px] font-bold tracking-widest text-gray-400 uppercase">SUB-MILESTONES</div>
                            <div class="text-xl font-bold text-gray-900 mt-0.5" x-text="activeTemplate.subMilestonesCount">723</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">Detailed stage checks</div>
                        </div>
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
                                                x-text="track.count"></span>
                                        </button>
                                    </template>
                                </div>

                                <!-- Manage Tracks Action Button -->
                                <button 
                                    @click="manageTracks()" 
                                    class="w-full p-2 rounded-lg border border-dashed border-gray-300 hover:border-amber-400 hover:bg-amber-50/30 text-[11px] font-medium text-gray-600 hover:text-amber-900 transition-all duration-150 flex items-center justify-center gap-1">
                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                    <span>Manage tracks</span>
                                </button>
                            </div>

                            <!-- Inner Right: Phase Items Sequence -->
                            <div class="md:col-span-8 space-y-2.5">
                                
                                <!-- Phase Items List -->
                                <div class="space-y-2">
                                    <template x-for="(phase, index) in currentPhases" :key="phase.id">
                                        <div class="flex items-center justify-between p-2.5 bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-150 group">
                                            
                                            <!-- Left Handle & Title -->
                                            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                                <div class="drag-handle text-gray-300 group-hover:text-gray-500 transition-colors p-0.5" title="Reorder">
                                                    <i data-lucide="grip-vertical" class="w-3.5 h-3.5"></i>
                                                </div>
                                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                                <span class="text-xs font-semibold text-gray-900 truncate" x-text="phase.name"></span>
                                            </div>

                                            <!-- Right Action Icons -->
                                            <div class="flex items-center gap-2 pl-2 shrink-0">
                                                <button 
                                                    @click="toggleStar(phase)" 
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

                                <!-- Add Phase Action Button -->
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
        </div>

        <!-- ================= VIEW 2: PROJECT PORTFOLIO ================= -->
        <div x-show="currentView === 'projects'" x-cloak class="space-y-4">
            
            <!-- Portfolio Header with Add Project Button Top Right -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">Project Portfolio</h1>
                    <p class="text-xs text-gray-500 mt-0.5 max-w-3xl leading-relaxed">
                        Review the project list, narrow the view, and open the right project page for the next delivery decision.
                    </p>
                </div>

                <!-- Add Project Button Top Right -->
                <div class="flex items-center gap-2 self-start sm:self-center shrink-0">
                    <button 
                        @click="openAddProjectModal()" 
                        class="btn bg-amber-100/90 hover:bg-amber-200 text-amber-950 border border-amber-300/80 hover:border-amber-400 rounded-lg px-4 py-1.5 text-xs font-semibold shadow-xs transition-all duration-150 normal-case flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-3.5 h-3.5 text-amber-800"></i>
                        <span>Add project</span>
                    </button>
                </div>
            </header>

            <!-- Projects Grid -->
            <div class="space-y-3 pt-1">
                <div class="text-[10px] font-bold tracking-widest text-gray-400 uppercase px-1">
                    <span>PROJECTS</span>
                </div>

                <!-- 3-Column Compact Responsive Card Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="project in projects" :key="project.ref">
                        <div class="bg-white rounded-xl border border-gray-200/90 p-4 shadow-2xs hover:shadow-md hover:border-amber-300/80 transition-all duration-200 flex flex-col justify-between space-y-3 group">
                            
                            <!-- Card Header: Ref Code & Single Applied Track Tag -->
                            <div class="flex items-center justify-between text-[10px] font-mono text-gray-400">
                                <span class="font-medium" x-text="project.ref"></span>
                                <span class="bg-amber-100/80 text-amber-900 px-1.5 py-0.2 rounded font-sans text-[9px] font-medium border border-amber-200" title="Applied Track">1 Track Applied</span>
                            </div>

                            <!-- Card Body: Clean Project Title & Tags -->
                            <div class="space-y-2">
                                <h3 class="font-bold text-base text-gray-900 group-hover:text-amber-900 transition-colors" x-text="project.name"></h3>
                                
                                <!-- Tags List -->
                                <div class="flex flex-wrap items-center gap-1">
                                    <template x-for="tag in project.tags" :key="tag">
                                        <span class="text-[9px] font-medium bg-amber-50 text-amber-900 border border-amber-200/80 px-1.5 py-0.2 rounded" x-text="tag"></span>
                                    </template>
                                    <span x-show="project.moreTags" class="text-[9px] text-gray-400 font-medium" x-text="project.moreTags"></span>
                                </div>
                            </div>

                            <!-- Card Footer: Milestone & Sub-milestone Indicators + Go to project button -->
                            <div class="pt-3 border-t border-gray-100 space-y-2">
                                <!-- Milestone & Sub-Milestone Indicators -->
                                <div class="grid grid-cols-2 gap-1 text-[10px] font-mono text-gray-500">
                                    <div>
                                        <span>Milestones: </span>
                                        <span class="font-semibold text-gray-800" x-text="project.milestones"></span>
                                    </div>
                                    <div>
                                        <span>Sub-milestones: </span>
                                        <span class="font-semibold text-gray-800" x-text="project.subMilestones"></span>
                                    </div>
                                </div>

                                <!-- Go to project Button -->
                                <a 
                                    :href="'/projects/' + project.ref"
                                    @click.prevent="goToProject(project)"
                                    class="btn btn-xs w-full bg-amber-100/80 hover:bg-amber-200 text-amber-950 border border-amber-300/90 rounded-lg text-[11px] font-semibold normal-case flex items-center justify-center gap-1 shadow-2xs py-1">
                                    <span>Go to project</span>
                                    <i data-lucide="arrow-right" class="w-3 h-3 text-amber-800"></i>
                                </a>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

        </div>

    </div>

    <!-- Modals -->
    <!-- Add Project Modal -->
    <dialog id="add_project_modal" class="modal">
        <div class="modal-box bg-white rounded-xl border border-gray-200 p-4 max-w-sm">
            <h3 class="font-bold text-base text-gray-900">Add New Project</h3>
            <p class="py-1 text-[11px] text-gray-500">Create a new project to track in your portfolio.</p>
            <div class="space-y-2.5 mt-2">
                <div class="form-control w-full">
                    <label class="label py-0.5"><span class="label-text font-medium text-[11px]">Project Name</span></label>
                    <input type="text" x-model="newProject.name" placeholder="e.g., Skyline Tower" class="input input-sm input-bordered w-full rounded-lg focus:border-amber-400 text-xs" />
                </div>
                <div class="form-control w-full">
                    <label class="label py-0.5"><span class="label-text font-medium text-[11px]">Reference Code</span></label>
                    <input type="text" x-model="newProject.ref" placeholder="e.g., MC26-L-G2-ST1" class="input input-sm input-bordered w-full rounded-lg focus:border-amber-400 text-xs font-mono" />
                </div>
            </div>
            <div class="modal-action mt-4">
                <form method="dialog" class="flex gap-2">
                    <button class="btn btn-ghost btn-xs rounded-lg normal-case">Cancel</button>
                    <button type="button" @click="addProject()" class="btn bg-amber-200 hover:bg-amber-300 text-amber-900 border-amber-300 btn-xs rounded-lg normal-case">Add Project</button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Add Phase Modal -->
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

    <!-- Create Template Modal -->
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

    <!-- Alpine App State Logic -->
    <script>
        function mainApp() {
            return {
                currentView: '{{ $initialView ?? "templates" }}',
                mobileMenuOpen: false,
                sidebarWidth: 240,
                isResizing: false,
                toast: {
                    show: false,
                    message: ''
                },
                newPhaseName: '',
                newTemplate: {
                    name: '',
                    description: ''
                },
                newProject: {
                    name: '',
                    ref: ''
                },

                startResize(e) {
                    this.isResizing = true;
                    const startX = e.clientX;
                    const startWidth = this.sidebarWidth;
                    
                    const doDrag = (dragEvent) => {
                        if (!this.isResizing) return;
                        const newWidth = startWidth + (dragEvent.clientX - startX);
                        if (newWidth >= 160 && newWidth <= 380) {
                            this.sidebarWidth = newWidth;
                        }
                    };

                    const stopDrag = () => {
                        this.isResizing = false;
                        window.removeEventListener('mousemove', doDrag);
                        window.removeEventListener('mouseup', stopDrag);
                    };

                    window.addEventListener('mousemove', doDrag);
                    window.addEventListener('mouseup', stopDrag);
                },

                projects: [
                    {
                        ref: 'MC26-L-G-1-MT1',
                        name: 'Mesob Tower',
                        tags: ['Construction Track'],
                        moreTags: '',
                        milestones: '0/128',
                        subMilestones: '0/723'
                    },
                    {
                        ref: 'MC26-L-G1-AT1',
                        name: 'Atlas tower',
                        tags: ['Design Permit Track'],
                        moreTags: '',
                        milestones: '0/7',
                        subMilestones: '0/10'
                    },
                    {
                        ref: 'PM-DRAFT-001',
                        name: 'Aurora Mobility Campus',
                        tags: ['Construction Track'],
                        moreTags: '',
                        milestones: '0/128',
                        subMilestones: '0/723'
                    },
                    {
                        ref: 'PM-ACTIVE-002',
                        name: 'Harbor Logistics Hub',
                        tags: ['Supervision Track'],
                        moreTags: '',
                        milestones: '0/128',
                        subMilestones: '0/723'
                    },
                    {
                        ref: 'PM-ACTIVE-003',
                        name: 'Abyssinia Financial Center',
                        tags: ['Construction Track'],
                        moreTags: '',
                        milestones: '12/24',
                        subMilestones: '45/100'
                    },
                    {
                        ref: 'PM-HANDOVER-004',
                        name: 'Entoto Heights Complex',
                        tags: ['Handover Track'],
                        moreTags: '',
                        milestones: '60/60',
                        subMilestones: '200/200'
                    }
                ],
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

                openAddProjectModal() {
                    this.newProject = { name: '', ref: '' };
                    document.getElementById('add_project_modal').showModal();
                },

                addProject() {
                    if (!this.newProject.name.trim()) return;
                    const newProj = {
                        ref: this.newProject.ref.trim() || ('MC26-L-G-' + (this.projects.length + 1)),
                        name: this.newProject.name.trim(),
                        tags: ['Construction Track'],
                        moreTags: '',
                        milestones: '0/24',
                        subMilestones: '0/120'
                    };
                    this.projects.unshift(newProj);
                    document.getElementById('add_project_modal').close();
                    this.showNotification(`Project '${newProj.name}' created!`);
                    this.refreshIcons();
                },

                goToProject(project) {
                    window.history.pushState({}, '', '/projects/' + project.ref);
                    this.showNotification(`Opening project details for '${project.name}' (${project.ref})...`);
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
                    this.showNotification(`Template track '${this.activeTemplate.name}' applied to project (1 track limit enforced).`);
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

@props(['active' => ''])

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
        <!-- App Branding Header -->
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
            
            <!-- Projects / Templates Navigation Link -->
            <a 
                href="{{ route('templates') }}"
                class="{{ request()->routeIs('templates') ? 'bg-amber-100/80 text-amber-950 font-semibold border-amber-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 border-transparent' }} w-full flex items-center justify-between px-3 py-2 rounded-lg border text-xs transition-all duration-150 text-left group">
                <div class="flex items-center gap-2 min-w-0">
                    <i data-lucide="folder-kanban" class="w-3.5 h-3.5 shrink-0 {{ request()->routeIs('templates') ? 'text-amber-800' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span class="truncate">Projects</span>
                </div>
                <span class="{{ request()->routeIs('templates') ? 'bg-amber-900/10 text-amber-900' : 'bg-gray-100 text-gray-500' }} text-[10px] font-bold px-1.5 py-0.2 rounded-full shrink-0">6</span>
            </a>

            <!-- Project Portfolio Navigation Link -->
            <a 
                href="{{ route('projects.index') }}"
                class="{{ request()->routeIs('projects.*') ? 'bg-amber-100/80 text-amber-950 font-semibold border-amber-300' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 border-transparent' }} w-full flex items-center justify-between px-3 py-2 rounded-lg border text-xs transition-all duration-150 text-left group">
                <div class="flex items-center gap-2 min-w-0">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5 shrink-0 {{ request()->routeIs('projects.*') ? 'text-amber-800' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    <span class="truncate">Project Portfolio</span>
                </div>
                <span class="{{ request()->routeIs('projects.*') ? 'bg-amber-900/10 text-amber-900' : 'bg-gray-100 text-gray-500' }} text-[10px] font-bold px-1.5 py-0.2 rounded-full shrink-0">6</span>
            </a>
        </nav>
    </div>
</aside>

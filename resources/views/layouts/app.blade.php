<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" class="h-full bg-[#FAF9F6]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Project Cycle - Delivery System' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite CSS (Tailwind & DaisyUI) -->
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js for Interactive UI state -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>


    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FAF9F6;
            color: #1F2937;
            font-size: 13px;
        }
        .drag-handle { cursor: grab; }
        .drag-handle:active { cursor: grabbing; }
        .resizer-handle { cursor: col-resize; user-select: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen antialiased bg-[#FAF9F6] text-gray-800 flex flex-col md:flex-row select-none" x-data="layoutApp()" :class="{'cursor-col-resize': isResizing}">

    <!-- Mobile Header -->
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

    <!-- App Resizable Navigation Sidebar Component -->
    <x-sidebar />

    <!-- Main Content Area -->
    <main class="flex-1 p-3 md:p-6 max-w-[1600px] w-full mx-auto overflow-x-hidden">

        <!-- Toast Notifications -->
        <div x-show="toast.show" x-transition 
             class="fixed top-4 right-4 z-50 alert alert-success shadow-md text-white text-xs font-medium max-w-sm py-2 px-3"
             style="display: none;">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            <span x-text="toast.message"></span>
        </div>

        {{ $slot }}

    </main>

    <script>
        function layoutApp() {
            return {
                mobileMenuOpen: false,
                sidebarWidth: 240,
                isResizing: false,
                toast: { show: false, message: '' },

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

                showNotification(msg) {
                    this.toast.message = msg;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3000);
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

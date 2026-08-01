<x-app-layout title="Project Portfolio - Project Cycle">

    <div class="space-y-4" x-data="projectsPortfolio()">
        
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

        <!-- Projects Gallery Grid -->
        <div class="space-y-3 pt-1">
            <div class="text-[10px] font-bold tracking-widest text-gray-400 uppercase px-1">
                <span>PROJECTS</span>
            </div>

            <!-- 3-Column Gallery View using Reusable Blade Components -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($projects as $project)
                    <x-project-card :project="$project" />
                @endforeach
            </div>
        </div>

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

    </div>

    <script>
        function projectsPortfolio() {
            return {
                newProject: { name: '', ref: '' },

                openAddProjectModal() {
                    this.newProject = { name: '', ref: '' };
                    document.getElementById('add_project_modal').showModal();
                },

                addProject() {
                    if (!this.newProject.name.trim()) return;
                    document.getElementById('add_project_modal').close();
                    this.$root.showNotification(`Project '${this.newProject.name}' added successfully!`);
                }
            }
        }
    </script>
</x-app-layout>

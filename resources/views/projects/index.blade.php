<x-app-layout title="Project Portfolio - Project Cycle">

    @php
        $createFields = [
            [
                'name' => 'name',
                'label' => 'Project Name',
                'type' => 'text',
                'placeholder' => 'e.g., Skyline Tower',
                'required' => true,
            ],
            [
                'name' => 'ref',
                'label' => 'Reference Code',
                'type' => 'text',
                'placeholder' => 'e.g., MC26-L-G2-ST1',
                'required' => true,
            ],
            [
                'name' => 'project_template_id',
                'label' => 'Initial Stage Template',
                'type' => 'select',
                'options' => array_replace(['' => '-- No Template (Custom) --'], $templates->pluck('name', 'id')->toArray()),
            ],
        ];

        $editFields = [
            [
                'name' => 'name',
                'label' => 'Project Name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'ref',
                'label' => 'Reference Code',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'status',
                'label' => 'Project Status',
                'type' => 'select',
                'options' => [
                    'Upcoming' => 'Upcoming',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed',
                    'On Hold' => 'On Hold',
                ],
            ],
        ];
    @endphp

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

        <!-- Add Project Dynamic Modal Component -->
        <x-form-modal
            id="add_project_modal"
            title="Add New Project"
            description="Create a new project to track in your portfolio."
            :action="route('projects.store')"
            method="POST"
            :fields="$createFields"
            submitText="Save Project"
            submitClass="bg-amber-200 hover:bg-amber-300 text-amber-950 border-amber-300"
            maxWidth="max-w-sm"
        />

        <!-- Edit Project Dynamic Modal Component -->
        <dialog id="edit_project_modal" class="modal">
            <div class="modal-box bg-white rounded-xl border border-gray-200 p-4 shadow-xl max-w-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-base text-gray-900">Edit Project</h3>
                        <p class="py-0.5 text-[11px] text-gray-500">Update project details and status.</p>
                    </div>
                    <button type="button" onclick="document.getElementById('edit_project_modal').close()" class="btn btn-ghost btn-xs btn-circle text-gray-400">✕</button>
                </div>

                <form method="POST" :action="'/projects/' + editProject.currentRef" class="mt-3 space-y-3">
                    @csrf
                    @method('PUT')

                    <div class="form-control w-full">
                        <label class="label py-0.5"><span class="label-text font-medium text-[11px] text-gray-700">Project Name *</span></label>
                        <input type="text" name="name" x-model="editProject.name" required class="input input-sm input-bordered w-full rounded-lg focus:border-amber-400 text-xs" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-0.5"><span class="label-text font-medium text-[11px] text-gray-700">Reference Code *</span></label>
                        <input type="text" name="ref" x-model="editProject.ref" required class="input input-sm input-bordered w-full rounded-lg focus:border-amber-400 text-xs font-mono" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-0.5"><span class="label-text font-medium text-[11px] text-gray-700">Project Status</span></label>
                        <select name="status" x-model="editProject.status" class="select select-sm select-bordered w-full rounded-lg focus:border-amber-400 text-xs bg-white">
                            <option value="Upcoming">Upcoming</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="On Hold">On Hold</option>
                        </select>
                    </div>

                    <div class="modal-action mt-4 flex items-center justify-end gap-2 border-t border-gray-100 pt-3">
                        <button type="button" onclick="document.getElementById('edit_project_modal').close()" class="btn btn-ghost btn-xs rounded-lg normal-case">Cancel</button>
                        <button type="submit" class="btn btn-xs rounded-lg normal-case font-semibold bg-amber-200 hover:bg-amber-300 text-amber-950 border-amber-300">
                            <i data-lucide="save" class="w-3.5 h-3.5 mr-1"></i>Update Project
                        </button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop"><button>close</button></form>
        </dialog>

        <!-- Delete Project Confirmation Modal Popup -->
        <dialog id="delete_project_modal" class="modal">
            <div class="modal-box bg-white rounded-xl border border-gray-200 p-4 shadow-xl max-w-sm">
                <div class="flex items-center gap-3 text-rose-600 mb-2">
                    <div class="p-2 bg-rose-50 rounded-full border border-rose-100">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-gray-900">Delete Project</h3>
                        <p class="text-[11px] text-gray-500">Are you sure you want to proceed?</p>
                    </div>
                </div>

                <div class="p-2.5 bg-rose-50/60 rounded-lg border border-rose-100/80 my-3">
                    <p class="text-xs text-rose-900">
                        You are about to permanently delete <strong class="font-semibold text-rose-950" x-text="deleteProject.name"></strong> (<span class="font-mono text-xs" x-text="deleteProject.ref"></span>).
                    </p>
                    <p class="text-[10px] text-rose-700/80 mt-1">This will remove all associated phases and milestone checkpoints. This action cannot be undone.</p>
                </div>

                <form method="POST" :action="'/projects/' + deleteProject.ref">
                    @csrf
                    @method('DELETE')
                    <div class="modal-action flex items-center justify-end gap-2 border-t border-gray-100 pt-3">
                        <button type="button" onclick="document.getElementById('delete_project_modal').close()" class="btn btn-ghost btn-xs rounded-lg normal-case">Cancel</button>
                        <button type="submit" class="btn bg-rose-600 hover:bg-rose-700 text-white btn-xs rounded-lg normal-case border-rose-600">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1"></i>Yes, Delete Project
                        </button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop"><button>close</button></form>
        </dialog>

    </div>

    <script>
        function projectsPortfolio() {
            return {
                editProject: { currentRef: '', ref: '', name: '', status: 'Upcoming' },
                deleteProject: { ref: '', name: '' },

                init() {
                    // Check if there are validation errors to reopen the add modal
                    @if ($errors->any())
                        // Laravel redirects back on validation error with a GET request.
                        // Standard POST forms don't have a '_method' field, so if old('_method') is empty, it was an add project request.
                        // If old('_method') is PUT/DELETE, it was an edit/delete request.
                        @if (!old('_method') || old('_method') === 'POST')
                            this.$nextTick(() => {
                                this.openAddProjectModal();
                            });
                        @endif
                    @endif
                },

                openAddProjectModal() {
                    document.getElementById('add_project_modal').showModal();
                },

                openEditModal(project) {
                    this.editProject = {
                        currentRef: project.ref,
                        ref: project.ref,
                        name: project.name,
                        status: project.status || 'Upcoming'
                    };
                    document.getElementById('edit_project_modal').showModal();
                },

                openDeleteModal(project) {
                    this.deleteProject = {
                        ref: project.ref,
                        name: project.name
                    };
                    document.getElementById('delete_project_modal').showModal();
                }
            }
        }
    </script>
</x-app-layout>

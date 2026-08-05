@props([
    'id',
    'title',
    'description' => null,
    'action',
    'method' => 'POST',
    'fields' => [],
    'submitText' => 'Save',
    'cancelText' => 'Cancel',
    'submitClass' => 'bg-amber-200 hover:bg-amber-300 text-amber-950 border-amber-300',
    'maxWidth' => 'max-w-md',
])

@php
    $realMethod = strtoupper($method);
    $spoofedMethod = in_array($realMethod, ['PUT', 'PATCH', 'DELETE']) ? $realMethod : null;
    $formMethod = $spoofedMethod ? 'POST' : $realMethod;
@endphp

<dialog id="{{ $id }}" class="modal">
    <div class="modal-box bg-white rounded-xl border border-gray-200 p-4 shadow-xl {{ $maxWidth }}">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="font-bold text-base text-gray-900">{{ $title }}</h3>
                @if($description)
                    <p class="py-0.5 text-[11px] text-gray-500 leading-normal">{{ $description }}</p>
                @endif
            </div>
            <button 
                type="button" 
                onclick="document.getElementById('{{ $id }}').close()" 
                class="btn btn-ghost btn-xs btn-circle text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        <form method="{{ $formMethod }}" action="{{ $action }}" class="mt-3">
            @csrf
            @if($spoofedMethod)
                @method($spoofedMethod)
            @endif

            <div class="space-y-3">
                @foreach($fields as $field)
                    @php
                        $fieldType = $field['type'] ?? 'text';
                        $fieldName = $field['name'];
                        $fieldLabel = $field['label'] ?? ucfirst($fieldName);
                        $fieldValue = old($fieldName, $field['value'] ?? '');
                        $fieldRequired = !empty($field['required']);
                        $fieldPlaceholder = $field['placeholder'] ?? '';
                        $fieldOptions = $field['options'] ?? [];
                        $fieldReadonly = !empty($field['readonly']);
                    @endphp

                    @if($fieldType === 'hidden')
                        <input type="hidden" name="{{ $fieldName }}" value="{{ $fieldValue }}" />
                    @else
                        <div class="form-control w-full">
                            @if($fieldLabel)
                                <label class="label py-0.5">
                                    <span class="label-text font-medium text-[11px] text-gray-700">
                                        {{ $fieldLabel }}
                                        @if($fieldRequired)<span class="text-rose-500">*</span>@endif
                                    </span>
                                </label>
                            @endif

                            @if($fieldType === 'select')
                                <select 
                                    name="{{ $fieldName }}" 
                                    @if($fieldRequired) required @endif
                                    @if($fieldReadonly) disabled @endif
                                    class="select select-sm select-bordered w-full rounded-lg focus:border-amber-400 text-xs bg-white">
                                    @foreach($fieldOptions as $optVal => $optLabel)
                                        @php
                                            $val = is_numeric($optVal) && !is_array($fieldOptions) ? $optLabel : $optVal;
                                            $lbl = is_array($fieldOptions) ? $optLabel : $optLabel;
                                            // Handle key-value arrays or associative options
                                            if (is_array($optLabel)) {
                                                $val = $optLabel['value'] ?? $optVal;
                                                $lbl = $optLabel['label'] ?? $val;
                                            }
                                        @endphp
                                        <option value="{{ $val }}" @selected((string)$fieldValue === (string)$val)>
                                            {{ $lbl }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif($fieldType === 'textarea')
                                <textarea 
                                    name="{{ $fieldName }}" 
                                    placeholder="{{ $fieldPlaceholder }}"
                                    rows="{{ $field['rows'] ?? 3 }}"
                                    @if($fieldRequired) required @endif
                                    @if($fieldReadonly) readonly @endif
                                    class="textarea textarea-sm textarea-bordered w-full rounded-lg focus:border-amber-400 text-xs">{{ $fieldValue }}</textarea>
                            @else
                                <input 
                                    type="{{ $fieldType }}" 
                                    name="{{ $fieldName }}" 
                                    value="{{ $fieldValue }}" 
                                    placeholder="{{ $fieldPlaceholder }}"
                                    @if($fieldRequired) required @endif
                                    @if($fieldReadonly) readonly @endif
                                    class="input input-sm input-bordered w-full rounded-lg focus:border-amber-400 text-xs @if($fieldType === 'code' || str_contains($fieldName, 'ref')) font-mono @endif" />
                            @endif

                            @error($fieldName)
                                <span class="text-[10px] text-rose-500 mt-0.5">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Extra custom slot content if passed -->
            {{ $slot }}

            <div class="modal-action mt-4 flex items-center justify-end gap-2 border-t border-gray-100 pt-3">
                <button 
                    type="button" 
                    onclick="document.getElementById('{{ $id }}').close()" 
                    class="btn btn-ghost btn-xs rounded-lg normal-case text-gray-600 hover:bg-gray-100">
                    {{ $cancelText }}
                </button>
                <button 
                    type="submit" 
                    class="btn btn-xs rounded-lg normal-case font-semibold shadow-xs {{ $submitClass }}">
                    {{ $submitText }}
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

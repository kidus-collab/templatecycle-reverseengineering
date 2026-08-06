<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Rules for Both Create & Update
        $rules = [ 'name' => 'required|string|max:255'];

        // Check if this is the progress update route
        if ($this->routeIs('projects.progress.update')) {
            return [
                'phases' => 'required|array',
                'phases.*.id' => 'required|exists:project_phases,id',
                'phases.*.status' => 'required|string',
                'phases.*.progress' => 'required|numeric',
                'phases.*.checkpoints' => 'array',
                'phases.*.checkpoints.*.id' => 'required|exists:milestone_checkpoints,id',
                'phases.*.checkpoints.*.done' => 'required|boolean',
            ];
        }
        // 2. Rules for CREATE 
        if ($this->isMethod('POST')) {
            $rules['name'] = 'required|string|max:255|unique:projects,name';
            $rules['ref'] = 'required|string|max:255|unique:projects,ref';
            $rules['project_template_id'] = 'nullable|exists:project_templates,id';
        }
           
        // 3. Rules for  UPDATE 
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $projectId = $this->route('project') ? $this->route('project')->id : null;
            
            if ($projectId) {
                $rules['name'] = 'required|string|max:255|unique:projects,name,' . $projectId;
                $rules['ref'] = 'required|string|max:255|unique:projects,ref,' . $projectId;
            }
            $rules['status'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    // Error Message for duplciate records 
    public function messages(): array
    {
        return [
            'unique' => 'Record is already available, please change the :attribute or it is a duplicate data error.',
        ];
    }
}

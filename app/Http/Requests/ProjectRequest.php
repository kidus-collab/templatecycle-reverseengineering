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
        // Rules for  Both Create & Update 
        $rules = [ 'name' => 'required|string|max:255'];

           // 2. Rules for CREATE 
           if ($this->isMethod('POST')) {
            $rules['ref'] = 'required|string|max:255|unique:projects,ref';
            $rules['project_template_id'] = 'nullable|exists:project_templates,id';
        }
           
              // 3. Rules for  UPDATE 
         if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $projectId = $this->route('project')->id;
            
            $rules['ref'] = 'required|string|max:255|unique:projects,ref,' . $projectId;
            $rules['status'] = 'nullable|string|max:255';
        }

        
              return $rules;
    }
}

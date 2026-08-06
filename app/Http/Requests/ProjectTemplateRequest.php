<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];

        // Template rules
        if ($this->routeIs('templates.store') || $this->routeIs('templates.update')) {
            $rules['name'] = 'required|string|max:255';
            $rules['description'] = 'nullable|string|max:1000';
            $rules['is_active'] = 'nullable|boolean';
        }

        // Track rules
        if ($this->routeIs('templates.tracks.store') || $this->routeIs('templates.tracks.update')) {
            $rules['name'] = 'required|string|max:255';
            $rules['phase_name'] = 'nullable|string|max:255';
        }

        // Milestone rules
        if ($this->routeIs('templates.milestones.store') || $this->routeIs('templates.milestones.update')) {
            $rules['title'] = 'required|string|max:255';
            $rules['parent_id'] = 'nullable|exists:milestones,id';
        }

        return $rules;
    }
}

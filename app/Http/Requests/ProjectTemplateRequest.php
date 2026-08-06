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

        // Apply rules
        if ($this->routeIs('templates.apply')) {
            $rules['project_id'] = 'required|exists:projects,id';
        }

        // Sync rules
        if ($this->routeIs('templates.sync')) {
            $rules['tracks'] = 'array|nullable';
            $rules['tracks.*.id'] = 'nullable';
            $rules['tracks.*.name'] = 'required|string';
            $rules['tracks.*.milestones'] = 'array|nullable';
            $rules['tracks.*.milestones.*.id'] = 'nullable';
            $rules['tracks.*.milestones.*.title'] = 'required|string';
            $rules['tracks.*.milestones.*.parent_id'] = 'nullable';
            // Allow sub_milestones explicitly so they don't get stripped from the payload
            $rules['tracks.*.milestones.*.sub_milestones'] = 'array|nullable';
            $rules['tracks.*.milestones.*.sub_milestones.*.id'] = 'nullable';
            $rules['tracks.*.milestones.*.sub_milestones.*.title'] = 'required|string';
            $rules['tracks.*.milestones.*.sub_milestones.*.parent_id'] = 'nullable';
        }

        return $rules;
    }
}

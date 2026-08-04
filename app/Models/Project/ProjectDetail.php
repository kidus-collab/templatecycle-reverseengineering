<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDetail extends Model
{
    protected $fillable = [
        'project_id',
        'applied_track',
        'progress_percent',
        'status',
    ];

    protected $casts = [
        'progress_percent' => 'float',
    ];

    public function project(): BelongsTo 
    {
        return $this->belongsTo(Project::class);
    }
}

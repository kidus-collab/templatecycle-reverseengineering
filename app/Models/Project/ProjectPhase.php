<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPhase extends Model
{
    protected $fillable = [
        'project_detail_id',
        'name',
        'status',
        'progress',
        'milestones_count',
        'sub_milestones_count',
    ];

    protected $casts = [
        'progress' => 'integer',
        'milestones_count' => 'integer',
        'sub_milestones_count' => 'integer',
    ];

    public function projectDetail(): BelongsTo 
    {
        return $this->belongsTo(ProjectDetail::class);
    }
}

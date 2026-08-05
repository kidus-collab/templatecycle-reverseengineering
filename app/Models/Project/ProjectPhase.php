<?php

declare(strict_types=1);

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'name'=> 'string',
        'status' => 'string',
        'progress' => 'integer',
        'milestones_count' => 'integer',
        'sub_milestones_count' => 'integer',
    ];

    public function projectDetail(): BelongsTo 
    {
        return $this->belongsTo(ProjectDetail::class);
    }

    public function milestoneCheckpoints(): HasMany
    {
        return $this->hasMany(MilestoneCheckpoint::class);
    }
}

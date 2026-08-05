<?php

declare(strict_types=1);

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectDetail extends Model
{
    protected $fillable = [
        'applied_track',
        'progress_percent',
        'status',
    ];

    protected $casts = [
        'applied_track' => 'string',
        'progress_percent' => 'float',
        'status' => 'string',
    ];

    public function project(): BelongsTo 
    {
        return $this->belongsTo(Project::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(ProjectPhase::class);
    }
}

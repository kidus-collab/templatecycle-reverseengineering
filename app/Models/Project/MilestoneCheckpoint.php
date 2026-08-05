<?php

declare(strict_types=1);

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MilestoneCheckpoint extends Model
{
    protected $fillable = [
        'title', 
        'done', 
    ];

    protected $casts = [
        'title' => 'string',
        'done' => 'boolean',
    ]; 

    public function projectPhase(): BelongsTo 
    {
        return $this->belongsTo(ProjectPhase::class);
    }
}

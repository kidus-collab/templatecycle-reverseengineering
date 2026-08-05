<?php

declare(strict_types=1);

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Track extends Model
{
    protected $fillable = [
        'name',
        'phase_name',
    ];

    protected $casts = [
        'name' => 'string',
        'phase_name' => 'string',
    ];

    public function projectTemplate(): BelongsTo 
    { 
        return $this->belongsTo(ProjectTemplate::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Models\Project;

use App\Models\Template\ProjectTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'name',
        'tags',
        'more_tags',
    ];

    protected $casts = [
        'ref' => 'string',
        'name' => 'string',
        'tags' => 'array',
        'more_tags' => 'string',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'ref';
    }

    public function projectTemplate(): BelongsTo
    {
        return $this->belongsTo(ProjectTemplate::class);
    }

    public function details(): HasOne
    {
        return $this->hasOne(ProjectDetail::class);
    }

    public function phases(): HasManyThrough
    {
        return $this->hasManyThrough(ProjectPhase::class, ProjectDetail::class);
    }
}

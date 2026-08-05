<?php

namespace App\Models\Project;

use App\Models\Template\ProjectTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory; 

    protected $fillable = [
        'project_template_id',
        'ref',
        'name',
        'tags',
        'more_tags',
    ];

    protected $casts = [
        'tags' => 'array',
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

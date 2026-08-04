<?php

namespace App\Models\Project;

use App\Models\Template\ProjectTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function projectTemplate(): BelongsTo
    {
        return $this->belongsTo(ProjectTemplate::class);
    }

    public function details(): HasOne
    {
        return $this->hasOne(ProjectDetail::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(ProjectPhase::class);
    }
}

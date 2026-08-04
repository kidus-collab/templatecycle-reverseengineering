<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    protected $fillable = [
        'track_id',
        'parent_id',
        'title',
        'starred',
    ];

    protected $casts = [
        'starred' => 'boolean',
    ]; 
    
    public function track(): BelongsTo 
    {
        return $this->belongsTo(Track::class);
    }

    public function parentMilestone(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function subMilestones(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}

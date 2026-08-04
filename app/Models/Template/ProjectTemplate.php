<?php

declare(strict_types=1); 

namespace App\Models\Template;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'tracks_count', 
        'milestones_count', 
        'sub_milestones_count', 
        'is_active',  
    ];
 
    protected $casts = [
         'tracks_count' => 'integer', 
         'milestones_count' => 'integer', 
         'sub_milestones_count' => 'integer', 
         'is_active' => 'boolean', 
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
     
    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class);
    }
}

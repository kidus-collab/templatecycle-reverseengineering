<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory; 
   

    protected $fillable = [
                'ref' ,
                'name',
                'appliedTrack' ,
                'progressPercent',
                'milestones',
                'subMilestones',
                'status' ,
                'phases',
    ];

    protected $cast = [
        // 
               'ref' => 'string',
                'name' => 'string',
                'appliedTrack => string' ,
                'progressPercent'=> 'double',
                'milestones' => 'string',
                'subMilestones' => 'string',
                'status' => 'string',
                'phases' => 'array',
    ];


}

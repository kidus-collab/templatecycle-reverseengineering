<?php

namespace App\Project\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDetails extends Model
{
       protected $fillable = [
                'ref' ,
                'name',
                'appliedTrack' ,
                'progressPercent',
                'milestones',
                'subMilestones',
                'status' ,
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
    ];

    public function project() : Belongsto 
    {
        return $this->Belongsto(Project::class , 'foreign_key');
    }

}

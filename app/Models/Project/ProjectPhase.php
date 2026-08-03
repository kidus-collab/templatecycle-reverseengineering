<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class phases extends Model
{
   protected $fillable = [
                        'name' ,
                        'status' ,
                        'progress',
                        'milestonesCount',
                        'subMilestonesCount',
    ];

    protected $cast = [
        // 
                 'name' => 'string',
                 'status' => 'string',
                 'milestonesCount' => 'string',
                 'subMilestonesCount' => 'string',
    ];

    public function projectDetails() : Belongsto 
    {
        return $this->Belongsto(ProjectDetails::class , 'foreign_key');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTemplate extends Model
{
    //
     use HasFactory;

   protected  $fillable = [
        'name',
        'description',
        'tracksCount', 
        'milestonesCount' , 
        'subMilestonesCount' , 
        'isActive' , 
        'tracks' ,  
    ];
 
  protected $cast = [
        'tracks' => 'array',
    ];

    public function projectsTemplates()
    {
        return $this->hasOne(Project::class);
    }
     

}

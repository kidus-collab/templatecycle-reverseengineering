<?php

declare(strict_types=1); 

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
    ];
 
  protected $cast = [
        // 
         'name' => 'string', 
         'description'=> 'string',
         'tracksCount'=> 'double', 
         'milestonesCount' => 'double', 
         'subMilestonesCount' => 'double', 
         'isActive' => 'boolean', 
    ];

    public function projectsTemplates()
    {
        return $this->hasOne(Project::class);
    }
     

}

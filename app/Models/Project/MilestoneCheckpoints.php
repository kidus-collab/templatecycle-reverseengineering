<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilestoneCheckpoints extends Model
{
    protected $fillable = [
        /** 
         *  So parent id null is the milestone 
         *  The remaining are submilesones 
         *  Title repreesnt the milestone tracks 
         *     */                   

         'parent_id' , 
         'title' , 
         'done' , 

    ];

    protected $cast = [
        // 
                  'parent_id'=>'integer' , 
                   'title'=> 'string' , 
                  'done' => 'boolean',
    ]; 

    public function phaseCheckpoint() : Belongsto 
    {
        return $this->Belongsto(Phases::class , 'foreign_key');
    }
}

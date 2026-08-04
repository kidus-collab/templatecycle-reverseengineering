<?php

namespace App\Template\Models;

use Illuminate\Database\Eloquent\Model;

class Milestones extends Model
{
    protected  $fillable = [
                   'milestone',
                   'sub-milestone',
      ]; 
    
      protected $cast = [
                    'milestone' => 'string',
                    'sub-milestone' => 'string',
      ];
    public function tracksMilestone() : Belongsto 
    {
        return $this->Belongsto(Tracks::class , 'foreign_key');
    }
}

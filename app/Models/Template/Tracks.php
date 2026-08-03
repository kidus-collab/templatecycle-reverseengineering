<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracks extends Model
{
  protected  $fillable = [
                 'name',
                 'phase_name',
    ];

  protected $casts = [
        // 
                'name' => 'string',
                'phase_name' => 'string',
    ];

    public function Templatetracks() : Belongsto { 
        
        return $this->Belongsto(ProjectTemplate::class , 'foreign_key');
    }
}

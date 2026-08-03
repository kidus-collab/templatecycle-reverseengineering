<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestones extends Model
{
    public function projectTemplate() : Belongsto 
    {
        return $this->Belongsto(Tracks::class , 'foreign_key');
    }
}

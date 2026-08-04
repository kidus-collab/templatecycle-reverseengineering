<?php
namespace App\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory; 
   

    protected $fillable = [
                'ref' ,
                'name',
                'tags' ,
                 'moreTags',
                'milestones',
                'subMilestones',
    ];

    protected $cast = [
        // 
                'ref' => 'string',
                'name' => 'string',
                'tags' => 'array',
                'moreTags' => 'string',
                'milestones' => 'string',
                'subMilestones' => 'string',
    ];


    public function templates()
    {
        return $this->hasOne(ProjectTemplate::class);
    }
}

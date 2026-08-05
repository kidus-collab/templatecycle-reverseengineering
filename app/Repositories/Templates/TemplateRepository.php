<?php 

declare(strict_types=1); 


namespace App\Repositories; 


use App\Models\Template; 


class TemplateRepository { 

     /** 
      *  Nested query  , Optimized report for specific store 
      */ 
  public function getAllTemplates() { 
      //   $templates = ProjectTemplate::with(['tracks.milestones'])->get();
      //   $templates = ProjectTemplate::all();
  }

}
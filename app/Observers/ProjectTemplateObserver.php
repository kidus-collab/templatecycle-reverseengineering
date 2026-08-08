<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Template\ProjectTemplate;
// use Illuminate\Support\Facades\Cache;

/**
 * @deprecated The cache observer is no longer used since templates are not being cached.
 */
class ProjectTemplateObserver
{
    public function saved(ProjectTemplate $template)
    {
        // clear cache when new template is created 
        // cache::tags(['templates'])->forget('templates.all');
    }

     public function deleted(ProjectTemplate $template)
    {
        // clear cache when new template is deleted 
        // Cache::tags(['templates'])->forget('templates.all');
    }

    public function updated(ProjectTemplate $template)
    {
        // clear cache when new template is updated 
        // Cache::tags(['templates'])->forget('templates.all');
    }
    
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Template\ProjectTemplate;
use App\Models\Project\Project;

class ProjectFeatureTest extends TestCase
{
    use RefreshDatabase;


    // Post test for creating a new project

    public function test_a_user_can_create_a_project()
    {
        $template = ProjectTemplate::create(['name' => 'Default Template']);

        $response = $this->post('/projects', [
            'name' => 'My New Test Project',
            'ref' => 'TEST-001',
            'project_template_id' => $template->id,
        ]);

        $response->assertRedirect('/projects');
        $this->assertDatabaseHas('projects', [
            'ref' => 'TEST-001',
            'name' => 'My New Test Project',
        ]);
    }

    // Put / Patch test for updating a project
    public function test_a_user_can_update_a_project()
    {
        
        $project = Project::create([
            'ref' => 'TEST-EDIT',
            'name' => 'Old Project Name',
        ]);

        
        $response = $this->put('/projects/' . $project->ref, [
            'name' => 'Updated Project Name',
            'ref' => 'TEST-EDIT', 
            'status' => 'In Progress'
        ]);

       
        $response->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'ref' => 'TEST-EDIT',
            'name' => 'Updated Project Name',
        ]);
    }

    // Project deletion test 
    public function test_a_user_can_delete_a_project()
    {
     
        $project = Project::create([
            'ref' => 'TEST-DELETE',
            'name' => 'Project to be deleted',
        ]);

  
        $this->assertDatabaseHas('projects', ['ref' => 'TEST-DELETE']);

        
        $response = $this->delete('/projects/' . $project->ref);


        $response->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', ['ref' => 'TEST-DELETE']);
    }
}
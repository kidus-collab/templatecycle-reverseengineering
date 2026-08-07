<?php

namespace Database\Factories\Project;

use App\Models\Project\Project;
use App\Models\Template\ProjectTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ref' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{4}'),
            'name' => $this->faker->company() . ' Project',
            'tags' => $this->faker->randomElements(['Commercial', 'Residential', 'Infrastructure', 'Design', 'Supervision'], $this->faker->numberBetween(1, 3)),
            'more_tags' => $this->faker->words(3, true),
            'project_template_id' => ProjectTemplate::inRandomOrder()->first()?->id,
        ];
    }
}

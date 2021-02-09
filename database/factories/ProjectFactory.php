<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'text' => $this->faker->text(300),
            'budget' => random_int(500, 100000),
            'followers_from' => random_int(0, 2) ? null : random_int(10000, 50000),
            'followers_to' => random_int(0, 2) ? null : random_int(100000, 500000),
            'user_id' => User::all()->random()->id
        ];
    }
}

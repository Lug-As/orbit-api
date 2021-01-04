<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Project;
use App\Models\Response;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResponseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Response::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text' => $this->faker->text(500),
            'account_id' => Account::all()->random()->id,
            'project_id' => Project::all()->random()->id,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Request::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => Str::lower(Str::random(10)),
            'about' => $this->faker->text(2000),
            'image' => 'PvsiXHPTwOE6.jpg',
            'user_id' => User::all()->random()->id,
        ];
    }
}

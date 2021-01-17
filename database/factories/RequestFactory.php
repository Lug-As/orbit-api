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
            'telegram' => Str::lower(Str::random(random_int(5, 12))),
            'phone' => '800' . random_int(1000000, 9999999),
            'email' => $this->faker->unique()->safeEmail,
            'user_id' => User::all()->random()->id,
        ];
    }
}

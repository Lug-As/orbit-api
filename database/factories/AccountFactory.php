<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => Str::lower(Str::random(10)),
            'about' => $this->faker->text(2000),
            'image' => 'PvsiXHPTwOE6.jpg',
            'telegram' => Str::lower(Str::random(random_int(5, 16))),
            'phone' => '800' . random_int(1000000, 9999999),
            'email' => $this->faker->unique()->safeEmail,
            'followers' => random_int(500, 1000000),
            'likes' => random_int(1500, 100000000),
            'user_id' => User::all()->random()->id,
        ];
    }
}

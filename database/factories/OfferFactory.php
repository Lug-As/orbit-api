<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text' => $this->faker->text(500),
            'user_id' => User::all()->random()->id,
            'account_id' => Account::all()->random()->id,
        ];
    }
}

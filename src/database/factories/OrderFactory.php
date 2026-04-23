<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => \App\Models\Item::factory(),
            'user_id' => \App\Models\User::factory(),
            'postal_code' => $this->faker->numerify('###-####'),
            'address' => $this->faker->address(),
            'building' => $this->faker->secondaryAddress(),
            'payment_method' => $this->faker->randomElement(['カード支払い', 'コンビニ払い']),
        ];
    }
}

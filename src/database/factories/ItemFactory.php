<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->word(),
            'image' => $this->faker->lexify('profile_id_????') . '.png',
            'price' => $this->faker->randomNumber(5),
            'brand' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'condition_id' => \App\Models\Condition::inRandomOrder()->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Item $item) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $item->categories()->attach($categories);
        });
    }

    public function singleCategory()
    {
        return $this->afterCreating(function (Item $item) {
            $item->categories()->sync([Category::inRandomOrder()->first()->id]);
        });
    }

    public function multipleCategories($count = 3)
    {
        return $this->afterCreating(function (Item $item) use ($count) {
            $categories = Category::inRandomOrder()->take($count)->get();
            $item->categories()->sync($categories);
        });
    }
}

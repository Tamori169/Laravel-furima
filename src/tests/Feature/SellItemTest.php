<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SellItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $file = UploadedFile::fake()->image('test_image.jpeg');
        $category = Category::first();
        $condition = Condition::first();

        $response = $this->actingAs($user)->get('/sell');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post('/sell', [
            'image' => $file,
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000,
        ]);
        $this->assertDatabaseHas('category_item', [
            'category_id' => $category->id,
        ]);
    }
}

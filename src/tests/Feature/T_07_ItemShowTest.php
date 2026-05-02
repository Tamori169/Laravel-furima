<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class T_07_ItemShowTest extends TestCase
{
    use RefreshDatabase;

    // ※いいねはFavoriteTestにてテストするため、0件とする
    public function test_必要な情報が表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->singleCategory()->create(['user_id' => $seller->id]);

        $item->comments()->create([
            'user_id' => $user->id,
            'comment' => 'テストコメント',
        ]);

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee($item->image);
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee(number_format($item->price));
        $response->assertSeeInOrder([
            '<p class="action__favorites-count">',
            '0',
            '</p>'
        ], false);
        $response->assertSeeInOrder([
            '<p class="action__comments-count">',
            '1',
            '</p>'
        ], false);
        $response->assertSee($item->description);
        $response->assertSee($item->categories->first()->name);
        $response->assertSee($item->condition->name);
        $response->assertSee($user->profile->image);
        $response->assertSee($user->name);
        $response->assertSee('テストコメント');
    }

    public function test_複数選択されたカテゴリが表示されているか()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->multipleCategories()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        $response->assertStatus(200);
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
    }
}

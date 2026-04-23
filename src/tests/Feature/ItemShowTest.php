<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    // 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
    // ※いいねはFavoriteTestにてテストするため、0件とする
    public function test_item_show_displays_all_information()
    {
        /** @var \App\Models\User $user1 */
        $user1 = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user1->id]);
        $user2 = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->singleCategory()->create(['user_id' => $user2->id]);

        $item->comments()->create([
            'user_id' => $user1->id,
            'comment' => 'テストコメント',
        ]);

        $response = $this->actingAs($user1)->get("/item/{$item->id}");

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
        $response->assertSee($user1->profile->image);
        $response->assertSee($user1->name);
        $response->assertSee('テストコメント');
    }

    // 複数選択されたカテゴリが表示されているか
    public function test_item_show_displays_all_categories()
    {
        /** @var \App\Models\User $user1 */
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->multipleCategories()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->get("/item/{$item->id}");

        $response->assertStatus(200);
        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
    }
}

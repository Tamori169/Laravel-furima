<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    // いいねアイコンを押下することによって、いいねした商品として登録することができる。
    public function test_user_can_favorite_an_item()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $user2->id]);

        $response = $this->post('/login', [
            'email' => $user1->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user1);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/favorite");
        $response->assertStatus(302);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '<p class="action__favorites-count">',
            '1',
            '</p>'
        ], false);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user1->id,
            'item_id' => $item->id,
        ]);
    }

    // 追加済みのアイコンは色が変化する
    public function test_user_sees_favorite_icon_when_item_is_favorited()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $user2->id]);

        $response = $this->post('/login', [
            'email' => $user1->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user1);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/favorite");
        $response->assertStatus(302);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('images/logos/ハートロゴ_ピンク.png');
    }

    // 再度いいねアイコンを押下することによって、いいねを解除することができる。
    public function test_user_can_unfavorite_an_item()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $user2->id]);

        $response = $this->post('/login', [
            'email' => $user1->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user1);

        $response = $this->get("/item/{$item->id}");

        $response = $this->post("/item/{$item->id}/favorite");

        $response = $this->get("/item/{$item->id}");

        $response = $this->delete("/item/{$item->id}/favorite");
        $response->assertStatus(302);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '<p class="action__favorites-count">',
            '0',
            '</p>'
        ], false);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user1->id,
            'item_id' => $item->id,
        ]);
    }
}

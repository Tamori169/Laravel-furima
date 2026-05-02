<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class T_08_FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねアイコンを押下することによっていいねした商品として登録することができる()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

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
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/favorite");
        $response->assertStatus(302);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('images/logos/favorite-logo-pink.png');
        $response->assertDontSee('images/logos/favorite-logo-default.png');
    }

    public function test_再度いいねアイコンを押下することによって、いいねを解除することができる()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
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
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    // 全商品を取得できる
    public function test_item_index_page_loads_correctly()
    {
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $items = Item::factory()->count(10)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    // 購入済み商品は「Sold」と表示される
    public function test_sold_items_show_sold_label()
    {
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create();

        Order::factory()->create(['item_id' => $item->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    // 自分が出品した商品は表示されない
    public function test_own_items_not_shown()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $user->id, 'name' => 'テスト商品']);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertDontSee($item->name);
    }

}

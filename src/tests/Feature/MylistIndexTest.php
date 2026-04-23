<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MylistIndexTest extends TestCase
{
    use RefreshDatabase;

    // いいねした商品だけが表示される
    public function test_only_favorited_items_shown()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item1 = Item::factory()->create(['name' => 'テスト商品']);
        $item2 = Item::factory()->create(['name' => 'サンプル商品']);

        $user->favorites()->attach($item1->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }

    // 購入済み商品は「Sold」と表示される
    public function test_sold_items_show_sold_label()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create();

        $user->favorites()->attach($item->id);

        Order::factory()->create(['item_id' => $item->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    // 未認証の場合は何も表示されない
    public function test_no_items_shown_for_guests()
    {
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);

        $item = Item::factory()->create(['name' => 'テスト商品']);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee($item->name);
    }
}
<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class T_05_MylistIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねした商品だけが表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item1 = Item::factory()->create(['name' => 'テスト商品', 'user_id' => $seller->id]);
        $item2 = Item::factory()->create(['name' => 'サンプル商品', 'user_id' => $seller->id]);

        $user->favorites()->attach($item1->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }

    public function test_購入済み商品はSoldと表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]  );

        $user->favorites()->attach($item->id);

        Order::factory()->create(['item_id' => $item->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_未認証の場合は何も表示されない()
    {
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);

        $item = Item::factory()->create(['name' => 'テスト商品']);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee($item->name);
    }
}
<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class T_04_ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
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

    public function test_購入済み商品はSoldと表示される()
    {
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create();

        Order::factory()->create(['item_id' => $item->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
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

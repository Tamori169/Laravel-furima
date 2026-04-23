<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    // 「商品名」で部分一致検索ができる
    public function test_search_by_item_name()
    {
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item1 = Item::factory()->create(['name' => 'テスト商品']);
        $item2 = Item::factory()->create(['name' => 'サンプル商品']);

        $response = $this->get("/?keyword=テスト");

        $response->assertStatus(200);
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }

    // 検索状態がマイリストでも保持されている
    public function test_search_by_item_name_in_mylist()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item1 = Item::factory()->create(['name' => 'テスト商品']);
        $item2 = Item::factory()->create(['name' => 'サンプル商品']);

        $user->favorites()->attach($item1->id);
        $user->favorites()->attach($item2->id);

        $response = $this->get("/?keyword=テスト");

        $response->assertStatus(200);
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);

        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=テスト');

        $response->assertStatus(200);
        $response->assertSee($item1->name);
        $response->assertDontSee($item2->name);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyPageTest extends TestCase
{
    use RefreshDatabase;

    // 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    public function test_gets_required_information()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item1 = Item::factory()->create(['user_id' => $user->id]);

        $seller = User::factory()->create();
        $item2 = Item::factory()->create(['user_id' => $seller->id]);
        $item3 = Item::factory()->create(['user_id' => $seller->id]);

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item2->id,
            ]);

        $response = $this->actingAs($user)->get('/mypage?page=sell');
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($profile->image);
        $response->assertSee($item1->name);

        $response = $this->actingAs($user)->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($profile->image);
        $response->assertSee($item2->name);
        $response->assertDontSee($item3->name);
    }
}

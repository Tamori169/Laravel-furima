<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Stripe\Checkout\Session;
use Tests\TestCase;

class T_10_OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_「購入する」ボタンを押下すると購入が完了する_カード支払い()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $mockSession = (object)['url' => 'https://checkout.stripe.com/test-url'];

        $mock = Mockery::mock('alias:' . Session::class);
        $mock->shouldReceive('create')->once()->andReturn($mockSession);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
        ->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'カード支払い',
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        $response->assertRedirect('https://checkout.stripe.com/test-url');
    }

    public function test_「購入する」ボタンを押下すると購入が完了する_コンビニ払い()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $mockSession = (object)['url' => 'https://checkout.stripe.com/test-url-konbini'];

        $mock = Mockery::mock('alias:' . Session::class);
        $mock->shouldReceive('create')->once()->andReturn($mockSession);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
            ->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        $response->assertRedirect('https://checkout.stripe.com/test-url-konbini');
    }


    public function test_購入した商品は商品一覧画面にて「sold」と表示される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        Order::factory()->create(['item_id' => $item->id, 'user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }


    public function test_プロフィールの購入した商品一覧に追加されている()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        Order::factory()->create(['item_id' => $item->id, 'user_id' => $user->id]);

        $response = $this->actingAs($user)->from('/')->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    public function test_出品者自身は購入手続きに進めない()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('出品者のため購入不可');
        $response->assertDontSee('購入する');
    }
}

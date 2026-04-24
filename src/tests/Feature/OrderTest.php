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

class OrderTest extends TestCase
{
    use RefreshDatabase;

    // 【カード支払い】「購入する」ボタンを押下すると購入が完了する
    public function test_user_can_buy_an_item_with_credit_card()
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

    // 【コンビニ払い】「購入する」ボタンを押下すると購入が完了する
    public function test_user_can_buy_an_item_with_convenience_payment()
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

    // 購入した商品は商品一覧画面にて「sold」と表示される

    public function test_sold_items_show_sold_label()
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

    // 「プロフィール/購入した商品一覧」に追加されている

    public function test_user_can_view_their_purchased_items()
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
}

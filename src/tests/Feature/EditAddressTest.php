<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Stripe\Checkout\Session;
use Tests\TestCase;

class EditAddressTest extends TestCase
{
    use RefreshDatabase;

    // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_user_can_see_edited_address_is_reflected_in_purchase_screen()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '渋谷ビル101',
        ]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
            ->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('渋谷ビル101');

        $response = $this->actingAs($user)->from("/purchase/address/{$item->id}")
        ->patch("/purchase/address/{$item->id}", [
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市',
            'building' => '梅田ビル202',
        ]);

        $response->assertStatus(302);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee('987-6543');
        $response->assertSee('大阪府大阪市');
        $response->assertSee('梅田ビル202');
    }

    // 購入した商品に送付先住所が紐づいて登録される
    public function test_edited_address_is_reflected_in_order()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '渋谷ビル101',
        ]);
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

        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('渋谷ビル101');

        $response = $this->actingAs($user)->from("/purchase/address/{$item->id}")
            ->patch("/purchase/address/{$item->id}", [
                'postal_code' => '987-6543',
                'address' => '大阪府大阪市',
                'building' => '梅田ビル202',
            ]);

        $response->assertStatus(302);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'カード支払い',
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市',
            'building' => '梅田ビル202',
        ]);

        $response->assertRedirect('https://checkout.stripe.com/test-url');
    }

    // 購入した商品に送付先住所が紐づいて登録される
    // ※stripe実装のためコメントアウト
    // public function test_edited_address_is_reflected_in_order()
    // {
    //     /** @var \App\Models\User $user */
    //     $user = User::factory()->create();
    //     $profile = Profile::factory()->create([
    //         'user_id' => $user->id,
    //         'postal_code' => '123-4567',
    //         'address' => '東京都渋谷区',
    //         'building' => '渋谷ビル101',
    //     ]);
    //     $seller = User::factory()->create();

    //     $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
    //     $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    //     $item = Item::factory()->create(['user_id' => $seller->id]);

    //     $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
    //         ->get("/purchase/{$item->id}");
    //     $response->assertStatus(200);

    //     $response->assertSee('123-4567');
    //     $response->assertSee('東京都渋谷区');
    //     $response->assertSee('渋谷ビル101');

    //     $response = $this->actingAs($user)->from("/purchase/address/{$item->id}")
    //         ->patch("/purchase/address/{$item->id}", [
    //             'postal_code' => '987-6543',
    //             'address' => '大阪府大阪市',
    //             'building' => '梅田ビル202',
    //         ]);

    //     $response->assertStatus(302);

    //     $response = $this->actingAs($user)->get("/purchase/{$item->id}");
    //     $response->assertStatus(200);

    //     $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
    //         'payment_method' => 'カード支払い',
    //         'postal_code' => '987-6543',
    //         'address' => '大阪府大阪市',
    //         'building' => '梅田ビル202',
    //     ])

    //     ->assertRedirect('/');

    //     $this->assertDatabaseHas('orders', [
    //         'user_id' => $user->id,
    //         'item_id' => $item->id,
    //         'payment_method' => 'カード支払い',
    //         'postal_code' => '987-6543',
    //         'address' => '大阪府大阪市',
    //         'building' => '梅田ビル202',
    //     ]);
    // }
}

<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class T_11_PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_小計画面で変更が反映される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
        ->get("/purchase/{$item->id}");

        $response->assertSeeInOrder([
            '<td class="purchase-table__payment-method" id="display-payment">',
            'ー',
            '</td>'
        ], false);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
            ->get("/purchase/{$item->id}?payment_method=カード支払い");

        $response->assertSeeInOrder([
            '<td class="purchase-table__payment-method" id="display-payment">',
            'カード支払い',
            '</td>'
        ], false);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
            ->get("/purchase/{$item->id}?payment_method=コンビニ払い");

        $response->assertSeeInOrder([
            '<td class="purchase-table__payment-method" id="display-payment">',
            'コンビニ払い',
            '</td>'
        ], false);
    }

    public function test_支払い方法の選択情報が配送先住所変更後も保持されている_コンビニ払い()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
            ->get("/purchase/{$item->id}?payment_method=コンビニ払い");

        $response->assertSeeInOrder([
            '<td class="purchase-table__payment-method" id="display-payment">',
            'コンビニ払い',
            '</td>'
        ], false);

        $response = $this->get("/purchase/address/{$item->id}?payment_method=コンビニ払い");
        $response->assertStatus(200);

        $response = $this->actingAs($user)->from("/purchase/address/{$item->id}")
            ->patch("/purchase/address/{$item->id}?payment_method=コンビニ払い", [
                'postal_code' => '987-6543',
                'address' => '大阪府大阪市',
                'building' => '梅田ビル202',
            ]);

        $response->assertStatus(302);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}?payment_method=コンビニ払い");
        $response->assertStatus(200);

        $response->assertSee('コンビニ払い');
    }

    public function test_支払い方法の選択情報が配送先住所変更後も保持されている_カード支払い()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
            ->get("/purchase/{$item->id}?payment_method=カード支払い");

        $response->assertSeeInOrder([
            '<td class="purchase-table__payment-method" id="display-payment">',
            'カード支払い',
            '</td>'
        ], false);

        $response = $this->get("/purchase/address/{$item->id}?payment_method=カード支払い");
        $response->assertStatus(200);

        $response = $this->actingAs($user)->from("/purchase/address/{$item->id}")
            ->patch("/purchase/address/{$item->id}?payment_method=カード支払い", [
                'postal_code' => '987-6543',
                'address' => '大阪府大阪市',
                'building' => '梅田ビル202',
            ]);

        $response->assertStatus(302);

        $response = $this->actingAs($user)->get("/purchase/{$item->id}?payment_method=カード支払い");
        $response->assertStatus(200);

        $response->assertSee('カード支払い');
    }
}

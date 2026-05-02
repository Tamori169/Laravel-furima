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
            ->withSession(['payment_method' => 'カード支払い'])
            ->get("/purchase/{$item->id}");

        $response->assertSeeInOrder([
            '<td class="purchase-table__payment-method" id="display-payment">',
            'カード支払い',
            '</td>'
        ], false);

        $response = $this->actingAs($user)->from('/')->from("/item/{$item->id}")
            ->withSession(['payment_method' => 'コンビニ払い'])
            ->get("/purchase/{$item->id}");

        $response->assertSeeInOrder([
            '<td class="purchase-table__payment-method" id="display-payment">',
            'コンビニ払い',
            '</td>'
        ], false);
    }
}
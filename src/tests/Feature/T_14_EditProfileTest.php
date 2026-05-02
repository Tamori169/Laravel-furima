<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class T_14_EditProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_変更項目が初期値として過去設定されていること()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'images/profiles/test_image.jpeg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '渋谷ビル101',
        ]);
        $response = $this->actingAs($user)->from('/')->from('/mypage')->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('value="テストユーザー"', false);
        $response->assertSee('src="' . asset('images/profiles/test_image.jpeg') . '"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('value="東京都渋谷区"', false);
        $response->assertSee('value="渋谷ビル101"', false);
    }
}

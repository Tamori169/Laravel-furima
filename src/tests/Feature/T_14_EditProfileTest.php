<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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
        $response->assertSee('src="' . Storage::url('images/profiles/test_image.jpeg') . '"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('value="東京都渋谷区"', false);
        $response->assertSee('value="渋谷ビル101"', false);

    }

    public function test_プロフィール未設定者でもプロフィール編集ができること()
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo(
            $user,
            VerifyEmail::class,
            function ($notification) use ($user) {
                $verificationUrl = $notification->toMail($user)->actionUrl;

                $response = $this->actingAs($user)->get($verificationUrl);

                $response->assertRedirect('/setup-profile');

                $this->assertNotNull($user->fresh()->email_verified_at);

                return true;
            }
        );

        $response = $this->actingAs($user)->from('/mypage')->get('/mypage/profile');

        $response->assertStatus(200);

        $response = $this->post('/setup-profile', [
            'name' => 'Test User',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '渋谷ビル101',
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '渋谷ビル101',
        ]);
    }
}

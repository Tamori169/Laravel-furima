<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_email_is_required()
    {
        $password = 'password';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->get('/login');

        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => '',
            'password' => $password,
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_password_is_required()
    {
        $user = User::factory()->create();

        $response = $this->get('/login');

        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    // 【メールアドレス編】入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_invalid_email_show_validation_error()
    {
        $email = 'test@example.com';
        $password = 'password';
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $response = $this->get('/login');

        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => $password,
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    // 【パスワード編】入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_invalid_password_show_validation_error()
    {
        $email = 'test@example.com';
        $password = 'password';
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $response = $this->get('/login');

        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    // 正しい情報が入力された場合、ログイン処理が実行される
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->get('/login');

        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
    }
}

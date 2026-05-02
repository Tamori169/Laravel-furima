<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class T_01_RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前が入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    public function test_メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_パスワードが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_パスワードが7文字以下の場合、バリデーションメッセージが表示される()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertSessionHasErrors(['password_confirmation' => 'パスワードと一致しません']);
    }

    // ※登録処理は、メール認証テスト（EmailVerificationTest）にて行うためコメントアウト

    // public function test_全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される()
    // {
    //     $response = $this->get('/register');

    //     $response->assertStatus(200);

    //     $response = $this->post('/register', [
    //         'name' => 'Test User',
    //         'email' => 'test@example.com',
    //         'password' => 'password',
    //         'password_confirmation' => 'password',
    //     ]);

    //     $response->assertRedirect('/setup-profile');
    // }
}
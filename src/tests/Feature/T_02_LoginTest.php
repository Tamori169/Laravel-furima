<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class T_02_LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
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

    public function test_パスワードが入力されていない場合、バリデーションメッセージが表示される()
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

    public function test_入力情報が間違っている場合、バリデーションメッセージが表示される_メールアドレス()
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

    public function test_入力情報が間違っている場合、バリデーションメッセージが表示される_パスワード()
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

    public function test_正しい情報が入力された場合、ログイン処理が実行される()
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

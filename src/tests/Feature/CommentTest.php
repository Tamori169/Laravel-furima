<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    // ログイン済みのユーザーはコメントを送信できる
    public function test_logged_in_user_can_post_comment()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->singleCategory()->create(['user_id' => $seller->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        $response = $this->from("/item/{$item->id}")->post("/item/{$item->id}/comment", [
            'comment' => 'テストコメント',
        ]);
        $response->assertStatus(302);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '<p class="action__comments-count">',
            '1',
            '</p>'
        ], false);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
    }

    // ログイン前のユーザーはコメントを送信できない
    public function test_guest_user_cannot_post_comment()
    {
        $user = User::factory()->create();
        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->singleCategory()->create(['user_id' => $user->id]);

        $response = $this->from("/item/{$item->id}")->post("/item/{$item->id}/comment", [
            'comment' => 'テストコメント',
        ]);
        $response->assertStatus(302)->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
    }

    // コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->singleCategory()->create(['user_id' => $seller->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        $response = $this->from("/item/{$item->id}")->post("/item/{$item->id}/comment", [
            'comment' => '',
        ]);
        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);
    }

    // コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_comment_cannot_be_more_than_255_characters()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $seller = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->singleCategory()->create(['user_id' => $seller->id]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        $response = $this->from("/item/{$item->id}")->post("/item/{$item->id}/comment", [
            'comment' => str_repeat('a', 256),
        ]);
        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以内で入力してください']);

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => str_repeat('a', 255),
        ]);
        $response->assertSessionHasNoErrors();
    }
}
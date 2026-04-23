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
        $user1 = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user1->id]);
        $user2 = User::factory()->create();

        $this->seed(\Database\Seeders\ConditionsTableSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $item = Item::factory()->singleCategory()->create(['user_id' => $user2->id]);

        $response = $this->post('/login', [
            'email' => $user1->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user1);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/comments", [
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
            'user_id' => $user1->id,
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

        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => 'テストコメント',
        ]);
        $response->assertStatus(302)->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
    }
}
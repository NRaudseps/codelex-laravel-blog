<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticlesControllerTest extends TestCase
{
    use RefreshDatabase;

    // This is a test for the index method
    /** @test */
    public function a_user_can_view_articles()
    {
        $article = Article::factory()->create();

        $response = $this->get('/articles');

        $response->assertStatus(200);
        $response->assertSee($article->title);
    }

    // This is a test for the create method
    /** @test */
    public function a_user_can_view_the_create_article_view()
    {
        $response = $this->get('/articles/create');

        $response->assertStatus(200);
    }

    // This is a test for the store method
    /** @test */
    public function a_user_can_create_a_new_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('articles.store'), [
            'user_id' => $user->id,
           'title' => $article->title,
           'content' => $article->content
        ]);

        $response->assertRedirect('/articles');

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);
    }

    // This test the show method
    /** @test */
    public function a_user_can_see_an_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/articles/' . $article->id);

        $response->assertStatus(200);
        $response->assertSee($article->title);
        $response->assertSee($article->content);
    }

    // This test the edit method
    /** @test */
    public function a_user_can_see_an_edit_article_view()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/articles/' . $article->id . '/edit');

        $response->assertStatus(200);
    }

    // This test the update method
    /** @test */
    public function a_user_can_update_an_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->put(route('articles.update', $article));

        $response->assertRedirect(route('articles.edit', $article));

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);
    }

    // This test the destroy method
    /** @test */
    public function a_user_can_delete_an_article()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);

        $this->followingRedirects();

        $response = $this->delete(route('articles.destroy', $article));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);
    }
}

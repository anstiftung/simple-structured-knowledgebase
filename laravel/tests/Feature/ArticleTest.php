<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\State;
use App\Models\Article;
use Database\Seeders\StateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = StateSeeder::class;

    public function test_index_all_articles(): void
    {
        $statePublished = State::where('key', 'publish')->first();

        Article::factory(15)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'state_id' => $statePublished->id,
                ]
            ))
            ->create();

        $countArticles = Article::where('state_id', $statePublished->id)
            ->where('deleted_at', null)
            ->count();

        echo $countArticles;

        $response = $this->get('/api/articles');
        $response
            ->assertStatus(200)
            ->assertJsonCount($countArticles, 'data');
    }

    public function test_show_single_article(): void
    {
        $article = Article::factory()
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'state_id' => State::where('key', 'publish')->first(),
                    'deleted_at' => null,
                ]
            ))
            ->create()
            ->first();

        $response = $this->get('/api/article/' . $article->slug);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $article->title]);
    }

    public function test_user_sees_own_articles_on_index()
    {
        /* This should test the api/articles route, if logged in the response should contain:
           - all published articles
           - all articles which are not deleted
           - all articles that belong to the authUser that are draft or in review
        */

        $user = User::factory()->create();

        // Creates 15 Published Articles
        Article::factory(5)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'state_id' => State::where('key', 'publish')->first(),
                    'deleted_at' => null
                ]
            ))
            ->create();

        // Creates 3 draft articles which are deleted Articles

        Article::factory(3)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'state_id' => State::where('key', 'draft')->first(),
                    'deleted_at' => Carbon::now(),
                ]
            ))
            ->create();

        // Creates 4 articles owned by authUser which are state draft
        Article::factory(4)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'state_id' => State::where('key', 'draft')->first(),
                    'deleted_at' => null,
                    'created_by_id' => $user->id
                ]
            ))
            ->create();

        $response = $this->actingAs($user)->get('/api/articles');
        $response
            ->assertStatus(200)
            ->assertJsonCount(9, 'data');

    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
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
}

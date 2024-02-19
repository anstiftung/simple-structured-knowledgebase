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
        $countArticles = 10;
        Article::factory($countArticles)
        ->state(new Sequence(
            fn(Sequence $sequence) => [
                'state_id' => State::all()->first(),
            ]
        ))
        ->create();

        $response = $this->get('/api/articles');
        $response
            ->assertStatus(200)
            ->assertJsonCount($countArticles, 'data');
    }

    public function test_show_single_article(): void
    {
        $article = Article::factory()
        ->state(new Sequence(
            fn(Sequence $sequence) => [
                'state_id' => State::where('key', 'publish')->first(),
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

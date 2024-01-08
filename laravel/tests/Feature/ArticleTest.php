<?php

namespace Tests\Feature;

use App\Models\Article;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_all_articles(): void
    {
        $countArticles = 10;
        Article::factory($countArticles)->create();

        $response = $this->get('/api/articles');
        $response
            ->assertStatus(200)
            ->assertJsonCount($countArticles, 'data');
    }

    public function test_index_single_article(): void
    {
        $article = Article::factory(1)->create()->first();

        $response = $this->get('/api/article/'.$article->slug);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $article->title]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\License;
use App\Models\Collection;
use App\Models\AttachedFile;
use App\Models\AttachedUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private $num_collections = 3;
    private $num_articles = 4;
    private $num_attached_files = 2;
    private $num_images = 2;
    private $num_attached_urls = 1;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create();
        License::factory()->create();

        AttachedFile::factory()->count($this->num_attached_files)
            ->state(new Sequence(
                fn(Sequence $sequence) => [
                    'mime_type' => 'application/pdf',
                    'license_id' => License::all()->first(),
                    'created_by_id' => User::all()->first(),
                    'updated_by_id' => User::all()->first()
                ],
            ))
            ->create();

        AttachedFile::factory()->count($this->num_images)
            ->state(new Sequence(
                fn(Sequence $sequence) => [
                    'mime_type' => 'image/png',
                    'license_id' => License::all()->first(),
                    'created_by_id' => User::all()->first(),
                    'updated_by_id' => User::all()->first()
                ],
            ))
            ->create();

        AttachedUrl::factory()->count($this->num_attached_urls)
            ->state(new Sequence(
                fn(Sequence $sequence) => [
                    'created_by_id' => User::all()->first(),
                    'updated_by_id' => User::all()->first()
                ],
            ))
            ->create();

        Article::factory()->count($this->num_articles)
        ->state(new Sequence(
            fn(Sequence $sequence) => [
                'created_by_id' => User::all()->first(),
                'updated_by_id' => User::all()->first()
            ],
        ))
        ->create();

        Collection::factory()->count($this->num_collections)
            ->state(new Sequence(
                fn(Sequence $sequence) => [
                    'created_by_id' => User::all()->first(),
                    'updated_by_id' => User::all()->first()
                ],
            ))
            ->create();
    }

    public function test_empty_search_query_returns_everything(): void
    {
        $response = $this->get('/api/search');

        $response->assertStatus(200);

        $content = json_decode($response->getContent());

        $this->assertTrue($content->meta->num_articles == Article::all()->count(), 'Wrong number of articles returned');
        $this->assertTrue($content->meta->num_collections == Collection::all()->count(), 'Wrong number of collections returned');
        $this->assertTrue($content->meta->num_attached_urls == AttachedUrl::valid()->get()->count(), 'Wrong number of attached_urls returned');
        $this->assertTrue($content->meta->num_attached_files == AttachedFile::valid()->get()->count(), 'Wrong number of attached_files returned');
        $this->assertTrue($content->meta->num_images == AttachedFile::whereIn('mime_type', ['image/png','image/jpg','image/jpeg'])->valid()->get()->count(), 'Wrong number of images returned');
    }

    public function test_search_type_article_returns_articles_only(): void
    {
        $response = $this->get('/api/search?types[]=articles');

        $response->assertStatus(200);

        $content = json_decode($response->getContent());

        $this->assertTrue(!property_exists($content->data, 'collections'), 'Data Property collections is not allowed to exist.');
        $this->assertTrue(!property_exists($content->data, 'attached_urls'), 'Data Property attached_urls is not allowed to exist.');
        $this->assertTrue(!property_exists($content->data, 'attached_files'), 'Data Property attached_files is not allowed to exist.');
        $this->assertTrue(!property_exists($content->data, 'images'), 'Data Property images is not allowed to exist.');

        $this->assertTrue($content->meta->num_articles == Article::all()->count(), 'Wrong number of articles returned');
    }
}

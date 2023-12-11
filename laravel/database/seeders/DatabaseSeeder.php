<?php

namespace Database\Seeders;

use App\Models\AttachedFile;
use App\Models\AttachedUrl;
use App\Models\User;
use Faker\Generator;
use App\Models\Article;
use App\Models\License;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    protected $faker;

    private $numLicenses = 4;
    private $numUsers = 10;
    private $numAttachments = 90;
    private $numArticles = 50;
    private $numCollections = 10;

    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        License::truncate();
        User::truncate();
        AttachedFile::truncate();
        AttachedUrl::truncate();
        Article::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        License::factory($this->numLicenses)->create();

        User::factory($this->numUsers)->create();

        AttachedFile::factory()->count($this->numAttachments)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'license_id' => License::all()->random()->id,
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))
            ->create();

        AttachedUrl::factory()->count($this->numAttachments)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))
            ->create();


        $articles = Article::factory()->count($this->numArticles)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))
            ->create();

        foreach ($articles as $article) {
            $numAttachmentsToAttach = rand(1, $this->numAttachments/2);
            $urls = AttachedUrl::get()->random($numAttachmentsToAttach/2);
            $files = AttachedFile::get()->random($numAttachmentsToAttach/2);
            $article->attached_urls()->attach($urls);
            $article->attached_files()->attach($files);
        }
    }
}

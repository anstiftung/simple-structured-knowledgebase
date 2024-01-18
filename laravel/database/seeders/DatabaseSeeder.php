<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use App\Models\Article;
use App\Models\License;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;

class DatabaseSeeder extends Seeder
{
    protected $faker;

    private $numLicenses = 4;
    private $numUsers = 10;
    private $numAttachments = 10;
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

        $generatedFiles = AttachedFile::factory()->count($this->numAttachments)
            ->state(new Sequence(
                fn(Sequence $sequence) => [
                    'license_id' => License::all()->random()->id,
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))
            ->create();

        AttachedUrl::factory()->count($this->numAttachments)
            ->state(new Sequence(
                fn(Sequence $sequence) => [
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))
            ->create();


        $articles = Article::factory()->count($this->numArticles)
            ->state(new Sequence(
                fn(Sequence $sequence) => [
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))
            ->create();

        foreach ($articles as $article) {
            $numAttachmentsToAttach = rand(1, $this->numAttachments / 2);
            $urls = AttachedUrl::get()->random($numAttachmentsToAttach / 2);
            $files = AttachedFile::get()->random($numAttachmentsToAttach / 2);
            $article->attached_urls()->attach($urls);
            $article->attached_files()->attach($files);
        }

        foreach ($generatedFiles as $file) {
            Storage::disk('uploads')->deleteDirectory($file->id);
            Storage::disk('uploads')->makeDirectory($file->id);

            $fullPath = storage_path('uploads/' . $file->id);
            $fakedImagePath = $this->faker->image($fullPath, 640, 480, null, true);
            // update generated file
            $file->filename = basename($fakedImagePath);
            $file->filesize = filesize($fakedImagePath);
            $file->mime_type = mime_content_type($fakedImagePath);
            $file->save();

            Process::run('chown -R www-data:www-data ' . $fullPath)->throw();
        }


        $this->call([
            RolesPermissionsSeeder::class,
        ]);
    }
}

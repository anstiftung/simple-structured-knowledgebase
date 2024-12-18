<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use App\Models\State;
use App\Models\Article;
use App\Models\Comment;
use App\Models\License;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\ArticleContentSeeder;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    protected $faker;

    private $numLicenses = 4;
    private $numUsers = 10;
    private $numEditors = 4;
    private $numAttachments = 10;
    private $numArticles = 50;

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
        Comment::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->call([
            RolesPermissionsSeeder::class,
            StateSeeder::class
        ]);

        License::factory($this->numLicenses)->create();
        // make shure there is one CC0 license for default selection
        $license = License::first()->update(['title' => 'CC0']);

        User::factory($this->numUsers)->create();

        // create some editors
        $editorUsers = User::all()->random($this->numEditors);
        foreach ($editorUsers as $editorUser) {
            $editorUser->assignRole('editor');
        }

        $generatedFiles = AttachedFile::factory()->count($this->numAttachments)
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

        // disable observer (and mail notifications)
        $articles = Article::factory()->count($this->numArticles)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id,
                    'state_id' => State::all()->random()->id
                ],
            ))
            ->create();

        foreach ($articles as $article) {
            Comment::factory()->count(rand(0, 5))
                ->state(new Sequence(
                    fn (Sequence $sequence) => [
                        'article_id' => $article->id,
                        'created_by_id' => User::all()->random()->id,
                        'updated_by_id' => User::all()->random()->id
                    ],
                ))
                ->create();
        }

        foreach ($generatedFiles as $file) {
            Storage::disk('uploads')->deleteDirectory($file->id);
            Storage::disk('uploads')->makeDirectory($file->id);

            $fullPath = storage_path('uploads/' . $file->id);
            $fakedImagePath = $this->faker->image($fullPath, 640, 480, null, true);
            if ($fakedImagePath) {
                // update generated file
                $file->filename = basename($fakedImagePath);
                $file->filesize = filesize($fakedImagePath);
                $file->mime_type = mime_content_type($fakedImagePath);
                $file->save();

                Process::run('chown -R www-data:www-data ' . $fullPath)->throw();
            }
        }

        $this->call([
            CollectionSeeder::class,
            ArticleContentSeeder::class,
        ]);
    }
}

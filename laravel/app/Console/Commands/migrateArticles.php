<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\AttachedFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class migrateArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:migrate-articles {--reattach : sync attachments to articles }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates datasets from the old cowiki_recipes table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipes = DB::select('select * from cowiki_recipes');

        foreach($recipes as $recipe) {
            $article = Article::make([
                'title' => $recipe->title,
                'slug' => Str::slug($recipe->title),
                'description' => $recipe->description,
                'updated_at' => $recipe->modified,
                'created_at' => $recipe->created,
            ]);

            $article->id = $recipe->id;

            $article->created_by_id = $recipe->created_by;
            $article->updated_by_id = $recipe->created_by;

            $article->save();
        }

        $reattach = $this->option('reattach');

        if($reattach) {
            $attachmentMap =  DB::select('select * from cowiki_ingredients_recipes');

            foreach($attachmentMap as $data) {
                $article = Article::find($data->recipe_id);
                $article->attached_files()->attach(AttachedFile::find($data->ingredient_id));
            }
        }
    }
}

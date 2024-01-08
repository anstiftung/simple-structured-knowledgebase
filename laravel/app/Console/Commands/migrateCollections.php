<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Collection;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class migrateArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:migrate-collections {--reattach : sync articles to collections }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates datasets from the old cowiki_collections table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $collections = DB::select('select * from cowiki_collections');

        foreach($collections as $old_collection) {
            $collection = Collection::make([
                'title' => $old_collection->title,
                'slug' => Str::slug($old_collection->title),
                'description' => $old_collection->description,
                'updated_at' => $old_collection->modified,
                'created_at' => $old_collection->created,
            ]);

            $collection->id = $old_collection->id;

            $collection->created_by_id = $old_collection->created_by;
            $collection->updated_by_id = $old_collection->created_by;

            $collection->save();
        }

        $reattach = $this->option('reattach');

        if($reattach) {
            $attachmentMap =  DB::select('select * from cowiki_collections_recipes');

            foreach($attachmentMap as $data) {
                $collection = Collection::find($data->collection_id);
                $collection->recipes()->attach(Article::find($data->recipe_id));
            }
        }
    }
}

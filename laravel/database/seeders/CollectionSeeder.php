<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Sequence;

class CollectionSeeder extends Seeder
{
    private $numCollections = 10;
    private $numAttachedArticlesMax = 15;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Collection::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $user = User::role('editor')->first();

        $collections = null;

        if($user) {
            $collections = Collection::factory()->count($this->numCollections)
            ->state(new Sequence(
                fn(Sequence $sequence) => [
                    'created_by_id' => $user->id,
                    'updated_by_id' => $user->id
                ],
            ))
            ->create();
        }

        if($collections) {
            foreach($collections as $collection) {
                $numAttachedArticles = rand(1, $this->numAttachedArticlesMax);
                $articles = Article::published()->get()->random($numAttachedArticles);

                $i = 0;
                $syncData = $articles->mapWithKeys(function ($item) use (&$i) {
                    $i++;
                    return [$item->id => ['order' => $i]];
                });
                $collection->articles()->sync($syncData);
            }
        }

        $i = 0;
        $featuredCollections = Collection::featured()->get();
        foreach($featuredCollections as $collection) {
            $collection->order = $i;
            $collection->save();
            $i++;
        }
    }
}

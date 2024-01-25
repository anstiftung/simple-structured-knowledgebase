<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CollectionSeeder extends Seeder
{
    private $numCollections = 10;
    private $numAttachedArticles = 5;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Collection::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $user = User::get()->random(1)->first();

        $collections = null;

        if($user) {
            $user->assignRole('editor');
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
                $articles = Article::get()->random($this->numAttachedArticles);
                $collection->articles()->sync($articles);
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

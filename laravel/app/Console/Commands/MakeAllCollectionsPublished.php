<?php

namespace App\Console\Commands;

use App\Models\Collection;
use Illuminate\Console\Command;

class MakeAllCollectionsPublished extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:make-all-collections-published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes all existing Collections Published';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $collections = Collection::all();
        foreach ($collections as $collection) {
            $collection->published = true;
            $collection->save();
        }
    }
}

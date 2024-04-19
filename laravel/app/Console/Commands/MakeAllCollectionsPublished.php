<?php

namespace App\Console\Commands;

use App\Models\Collection;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class MakeAllCollectionsPublished extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:make-all-collections-published {--force : Force the operation to run when in production}';

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
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $collections = Collection::all();
        foreach ($collections as $collection) {
            $collection->published = true;
            $collection->save();
        }
    }
}

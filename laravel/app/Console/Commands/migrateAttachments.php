<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\License;
use Illuminate\Support\Str;
use App\Models\AttachedFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class migrateAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:migrate-attachments {--with-licenses : Creates the licenses from cowiki_ingredients in the license-table, use on initial migration only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates datasets from the old cowiki_ingredients table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrateLicenses = $this->option('with-licenses');

        if($migrateLicenses) {
            $licenses_to_migrate =  DB::select('select UNIQUE(license) from cowiki_ingredients');

            foreach($licenses_to_migrate as $license) {
                $license = License::make([
                    'title' => $license->license,
                    'description' => 'no description yet...',
                    'active' => true
                ]);

                $license->save();
            }
        }

        $licenses = License::all();
        $ingredients = DB::select('select * from cowiki_ingredients');

        foreach($ingredients as $ingredient) {

            $license_id = $licenses->where('title', $ingredient->license)->first();

            if(User::find($ingredient->created_by)) {

                $attachment = AttachedFile::make([
                    'title' => $ingredient->title,
                    'filename' => $ingredient->filename,
                    'description' => Str::limit($ingredient->content,390,'…'),
                    'mime_type' => '',
                    'filesize' => '0',
                    'preview_file' => '',
                    'source' => $ingredient->source,
                    'license_id' => $license_id->id,
                    'updated_at' => $ingredient->modified,
                    'created_at' => $ingredient->created,
                ]);

                $attachment->id = $ingredient->id;
                $attachment->created_by_id = $ingredient->created_by;
                $attachment->updated_by_id = $ingredient->created_by;

                $attachment->save();

            } else {
                $this->line('Unable to find user for ingredient with id :'. $ingredient->id );
            }
        }
    }
}

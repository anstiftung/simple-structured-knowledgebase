<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AttachedFile;
use Exception;
use Illuminate\Support\Facades\Storage;

class downloadAttachedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:download-attached-files {--folder=: Specify download folder in default storage with trailing slash}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download files from old CoWiki into specified folder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('folder');
        $filesInDb = AttachedFile::all();

        foreach($filesInDb as $file) {
            $prefix_url = 'https://www.offene-werkstaetten.org/files/cowiki/';
            $url = $prefix_url . rawurlencode($file->filename);
            $filesystem_target_path = $path.$file->filename;
            try {
                Storage::disk('local')->put($filesystem_target_path, file_get_contents($url));

                $size = Storage::size($filesystem_target_path);
                $mime = Storage::mimeType($filesystem_target_path);

                $file->mime_type = $mime;
                $file->filesize = $size;
                $file->save();

                $this->info('Download: ' . $filesystem_target_path . ' success. ( '. $this::formatBytes($size) . ' , ' . $mime . ')');
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    public static function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}

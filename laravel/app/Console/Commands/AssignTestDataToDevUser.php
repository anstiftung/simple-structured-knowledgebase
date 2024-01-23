<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Article;
use App\Models\Collection;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Console\Command;

class AssignTestDataToDevUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:assign-test-data-to-dev-user {--userid=11}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns all Articles and Attachments to testing user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('userid');
        $user = User::find($userId);

        if (app()->environment(['production'])) {
            $this->error('You are not allowed to run this command in production.');
            return;
        }

        if (!$user) {
            $this->error('Unable to find user');
        } else {

            $files = AttachedFile::all();
            foreach ($files as $file) {
                $file->created_by()->associate($user);
                $file->updated_by()->associate($user);
                $file->save();
            }

            $urls = AttachedUrl::all();
            foreach ($urls as $url) {
                $url->created_by()->associate($user);
                $url->updated_by()->associate($user);
                $url->save();
            }

            $articles = Article::all();
            foreach ($articles as $article) {
                $article->created_by()->associate($user);
                $article->updated_by()->associate($user);
                $article->save();
            }

            $collections = Collection::all();
            foreach ($collections as $collection) {
                $collection->created_by()->associate($user);
                $collection->updated_by()->associate($user);
                $collection->save();
            }
        }

    }
}

<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class MigrateAttachedUrlLinks extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cowiki:migrate-attached-url-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates links to AttachedUrls to the new url format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $articles = Article::all();

        foreach ($articles as $article) {
            $pattern = '/<item-link\s+(.*?)href="([^"]*)"(.*?)>(.*?)<\/item-link>/';
            preg_match_all($pattern, $article->content, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                // Extract data-type attribute value
                $dataType = '';
                preg_match('/data-type="([^"]*)"/', $match[1], $idMatches);
                if (!empty($idMatches)) {
                    $dataType = $idMatches[1];
                }
                // Extract data-id attribute value
                $dataId = '';
                preg_match('/data-id="([^"]*)"/', $match[1], $idMatches);
                if (!empty($idMatches)) {
                    $dataId = $idMatches[1];
                } else {
                    $this->error('Unable to get data-id');
                }
                if ($dataType == 'AttachedUrl') {
                    $newHref = '/url/' . $dataId;

                    $this->info('Start Replacing');
                    // Replace href attribute with the computed value
                    $replacement = '<item-link ' . $match[1] . 'href="' . $newHref . '"' . $match[3] . '>' . $match[4] . '</item-link>';

                    // Replace the original match with the modified version
                    $article->content = str_replace($match[0], $replacement, $article->content);
                    $this->info($replacement);
                    $article->save();

                }
            }
        }
    }
}

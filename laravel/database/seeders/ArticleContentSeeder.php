<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Collection;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Database\Seeder;

class ArticleContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::all();
        foreach ($articles as $article) {
            $randomCollection = Collection::inRandomOrder()->limit(1)->first();
            $randomArticle = Article::inRandomOrder()->limit(1)->first();
            $randomAttachedURL = AttachedUrl::inRandomOrder()->limit(1)->first();
            $randomAttachedFile = AttachedFile::inRandomOrder()->limit(1)->first();

            $content = <<<EOD
                <h2>Eine große Überschrift (H2)</h2>
                <p>Wir
                    <strong>verwenden</strong>
                    einen
                    <em>größeren</em>
                    <u>Zeilenabstand</u>
                    für eine
                    <s>bessere</s>
                    Lesbarkeit und einem fluffigeren Auftreten.
                    <a rel="noopener noreferrer nofollow" data-type="Article" href="/beitrag/$randomArticle->slug">Lorem ipsum dolor
                    </a>sit amet,
                    <a rel="noopener noreferrer nofollow" data-type="Collection" href="/sammlung/$randomCollection->slug">consetetur sadipscing
                    </a>elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,
                    <a target="_blank" rel="noopener noreferrer nofollow" data-type="AttachedFile" href="/api/attached-file/$randomAttachedFile->id">consetetur</a>
                    sadipscing elitr, sed diam nonumy
                    <a target="_blank" rel="noopener noreferrer nofollow" data-type="AttachedUrl" href="$randomAttachedURL->url">eirmod tempor invidunt ut labore et dolore magna aliquyam erat,</a>
                    sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </p>
                <img src="/api/attached-file/$randomAttachedFile->id" alt="Baylee Crooks" title="(c) Numquam saepe sunt voluptates.">
                <h3>Eine Überschrift (h3)</h3>
                <ul>
                    <li>
                        <p>Und</p>
                    </li>
                    <li>
                        <p>eine liste gibt's auch</p>
                    </li>
                </ul>
                <h4>Eine Überschrift (h4)</h4>
                <ol>
                    <li>
                        <p>Und</p>
                    </li>
                    <li>
                        <p>auch</p>
                    </li>
                    <li>
                        <p>numerierte</p>
                    </li>
                    <li>
                        <p>listen</p>
                        <ol>
                            <li>
                                <p>gibt
                                </p>
                            </li>
                            <li>
                                <p>es</p>
                            </li>
                        </ol>
                    </li>
                </ol>
                <h3>Und Infoboxen</h3>
                <div class="info-box" data-type="warning">Hinweis</div>
                <div class="info-box" data-type="danger">Gefahr</div>
                <div class="info-box" data-type="question">Fragestellung: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>

            EOD;

            $article->content = $content;
            $article->save();
        }
    }
}

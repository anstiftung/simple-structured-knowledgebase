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
            $randomArticle = Article::published()->inRandomOrder()->limit(1)->first();
            $randomAttachedURL = AttachedUrl::valid()->inRandomOrder()->limit(1)->first();
            $randomAttachedFile = AttachedFile::valid()->inRandomOrder()->limit(1)->first();

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
                    <item-link data-type="Article" data-id="$randomArticle->id" href="/beitrag/$randomArticle->slug">Ein Link zu einem Beitrag</item-link>
                    accusam et justo
                    <item-link data-type="Collection" data-id="$randomCollection->id" href="/sammlung/$randomCollection->slug">Ein Link zu einer Sammlung</item-link>
                    consetetur sadipscing
                    elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,
                    sadipscing elitr, sed diam nonumy
                    <item-link data-type="AttachedUrl" data-id="$randomAttachedURL->id" href="/url/$randomAttachedURL->id" target="_blank">Ein Link zu einem Anhang (URL)</item-link>
                    sadipscing elitr, sed diam nonumy
                    <item-link data-type="AttachedFile" data-id="$randomAttachedFile->id" href="/anhang/$randomAttachedFile->id" target="_blank">Ein Link zu einem Anhang (File)</item-link>
                    sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </p>
                <img src="/api/attached-file/serve/$randomAttachedFile->id" alt="Baylee Crooks" title="(c) Numquam saepe sunt voluptates.">
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
                <div class="info-box" data-type="warning">
                    <svg viewBox="0 0 15 15" width="15px" height="15px"><use href="/icons/warning.svg#warning"></use></svg>
                    <div class="content">
                        Hinweis
                    </div>
                </div>
                <div class="info-box" data-type="danger">
                    <svg viewBox="0 0 15 15" width="15px" height="15px"><use href="/icons/danger.svg#danger"></use></svg>
                    <div class="content">
                        Gefahr
                    </div>
                </div>
                <div class="info-box" data-type="question">
                    <svg viewBox="0 0 15 15" width="15px" height="15px"><use href="/icons/question.svg#question"></use></svg>
                    <div class="content">
                        Fragestellung: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    </div>
                </div>

            EOD;

            $article->content = $content;
            $article->save();
        }
    }
}

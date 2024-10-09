<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Support\Str;

class ArticleAggregatorService
{
    private const NewsAPI_Source_ID = 1;

    private const TheGuardian_Source_ID = 2;

    private const NewYorkTimes_Source_ID = 3;

    //
    protected $newsAPIService;

    protected $theGuardianAPIService;

    protected $newYorkTimesAPIService;

    /**
     * ArticleAggregatorService constructor.
     *
     * @param NYTimesApiService newYorkTimesAPIService
     */
    public function __construct(NewsApiService $newsAPIService, TheGuardianApiService $theGuardianAPIService, NYTimesApiService $newYorkTimesAPIService)
    {
        // NewsAPIService
        $this->newsAPIService = $newsAPIService;
        // TheGuardianAPIService
        $this->theGuardianAPIService = $theGuardianAPIService;
        // NewYorkTimesAPIService
        $this->newYorkTimesAPIService = $newYorkTimesAPIService;
    }

    /**
     * Aggregate articles from different sources
     *
     * @return void
     */
    public function aggregateArticles()
    {
        $this->fetchAndStoreNewsAPIArticles();
        $this->fetchAndStoreTheGuardianAPIArticles();
        $this->fetchAndStoreNewYorkTimesAPIArticles();
    }

    /**
     * Fetch and store NewsAPI articles
     *
     * @return void
     */
    private function fetchAndStoreNewsAPIArticles()
    {
        // Fetch all categories
        $categories = Category::select('id', 'name')->get();
        // Loop through each category
        foreach ($categories as $category) {
            // Fetch top headlines for each category's slug
            $articles = $this->newsAPIService->fetchArticles(['category' => $category->name]);
            // Save the articles to the database
            $this->saveArticle($articles, $category->id, self::NewsAPI_Source_ID);
        }
    }

    /**
     * Fetch and store TheGuardian API articles
     *
     * @return void
     */
    private function fetchAndStoreTheGuardianAPIArticles()
    {
        // Fetch all categories
        $categories = Category::select('id', 'name')->get();

        // Loop through each category
        foreach ($categories as $category) {
            // Fetch top headlines for each category's slug
            $articles = $this->theGuardianAPIService->fetchArticles(['section' => $category->slug]);
            // Save the articles to the database
            $this->saveArticle($articles, $category->id, self::TheGuardian_Source_ID);
        }
    }

    /**
     * Fetch and store New York Times API articles
     *
     * @return void
     */
    private function fetchAndStoreNewYorkTimesAPIArticles()
    {
        // Fetch all categories
        $categories = Category::select('id', 'name')->get();

        // Loop through each category
        foreach ($categories as $category) {
            // Fetch top headlines for each category's slug
            $articles = $this->newYorkTimesAPIService->fetchArticles([
                'fq' => "news_desk:($category->name)",
            ]);
            // Save the articles to the database
            $this->saveArticle($articles, $category->id, self::NewYorkTimes_Source_ID);
        }
    }

    /**
     * Save the articles to the database
     *
     * @param  array  $articles,  int $category_id, int $news_source_id
     * @return void
     */
    private function saveArticle($articles, $category_id, $news_source_id)
    {
        // Loop through each article
        foreach ($articles as $article) {
            // FirstOrCreate the author
            $author = Author::firstOrCreate(
                ['name' => $article['author']],
                ['slug' => Str::slug($article['author'])]
            );
            // Save the article to the database
            $data[] = [
                'category_id' => $category_id,
                'author_id' => $author->id,
                'news_source_id' => $news_source_id,
                'title' => $article['title'],
                'description' => $article['description'],
                'content' => $article['content'],
                'url' => $article['url'],
                'image' => $article['image'],
                'published_at' => $article['published_at'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Bulk upsert the articles
            Article::upsert($data, ['url'], []);
        }
    }
}

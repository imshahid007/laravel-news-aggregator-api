<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Str;

class ArticleAggregatorService
{
    private const NewsAPI_Source_ID = 1;
    //
    protected $newsAPIService;

    public function __construct(NewsAPIService $newsAPIService,)
    {
        $this->newsAPIService = $newsAPIService;
    }

    public function aggregateArticles()
    {
        $this->fetchAndStoreNewsAPIArticles();
    }

    private function fetchAndStoreNewsAPIArticles()
    {
        // Fetch all categories
        $categories = Category::all();
        // Loop through each category
        foreach ($categories as $category) {
            // Fetch top headlines for each category's slug
            $articles = $this->newsAPIService->fetchArticles(['category' => $category->name]);
            // Save the articles to the database
            $this->saveArticle($articles, $category->id, self::NewsAPI_Source_ID);
        }
    }

    public function saveArticle($articles, $category_id, $news_source_id)
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
                'author_id'   => $author->id,
                'news_source_id' => $news_source_id,
                'title' => $article['title'],
                'description' => $article['description'],
                'content' => $article['content'],
                'url' => $article['url'],
                'image' => $article['image'],
                'published_at' => $article['published_at'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Bulk upsert the articles
            Article::upsert($data, ['url'], []);

        }
    }
}

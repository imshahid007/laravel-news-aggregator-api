<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    private $cache_duration;
    /**
     * Create a new ArticleController instance.
     */
    public function __construct()
    {
       $this->cache_duration = env('CACHE_DURATION');
    }
    /**
     * Display a listing of the articles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Cache articles
        $articles = Cache::remember('articles_list', $this->cache_duration, function () {
            $articles = Article::with('category', 'newsSource')
                ->select('id', 'category_id', 'news_source_id', 'author_id', 'title', 'description', 'content', 'url', 'image', 'published_at')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return ArticleResource::collection($articles);
        });
        return $articles;
        //


    }

    /* Display the specified article.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */

    public function show(Article $article)
    {
        // Cache the Article
        $article = Cache::remember("article_{$article->id}", $this->cache_duration, function () use ($article) {
            return new ArticleResource($article);
        });
        return $article;
    }

    /**
     * Search for articles to allow filtering articles by keyword, date, category and source
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Validate the request inputs to avoid any attacks
        $request->validate([
            'q' => 'string|min:3|nullable',
            'date' => 'date_format:Y-m-d|nullable', // must be in the format of Y-m-d
            'category' => 'integer|nullable|exists:categories,id',
            'source' => 'integer|nullable|exists:news_sources,id',
        ]);

        //
        $query = Article::query();

        // Check if the request has a query string
        if ($request->has('q')) {
            $q = $request->input('q');
            // Search for title and description
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('title', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            });
        }

        // Check if the request has a date filter
        if ($request->has('date')) {
            $query->whereDate('published_at', $request->input('date'));
        }
        // Check if the request has a category filter
        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }
        // Check if the request has a source filter
        if ($request->has('source')) {
            $query->where('news_source_id', $request->input('source'));
        }

        //
        $articles = $query->select('id', 'category_id', 'news_source_id', 'author_id', 'title', 'description', 'content', 'url', 'image', 'published_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        //
        return ArticleResource::collection($articles);
    }
}

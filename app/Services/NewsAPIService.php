<?php

namespace App\Services;

use Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAPIService
{
    private $apiKey;

    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('NEWS_API_SECRET_KEY');
        $this->baseUrl = 'https://newsapi.org/v2/';
        // Check if the News API secret key is set
        if (! $this->apiKey) {
            Log::error('News API secret key not found');
            //
            throw new \Exception('News API secret key not found');
        }
    }

    /**
     * Fetch articles from the News API
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function fetchArticles($params = [])
    {
        try {
            // Fetch articles from the News API
            $response = Http::get($this->baseUrl.'top-headlines', array_merge([
                'apiKey' => $this->apiKey,
                'sortBy' => 'publishedAt',
                'publishedAt' => date('Y-m-d'),
                'pageSize' => 100,
            ], $params));
            if ($response->successful()) {
                // Transform the response data
                return $this->transformArticles($response->json());
            } else {
                Log::error($response->json());

                return null;
            }
        } catch (Exception $e) {
            //
            Log::error($e->getMessage());

        }
    }

    /**
     * Transform the articles from the News API
     *
     * @param  array<string, mixed>  $articles
     * @return array<string, mixed>
     */
    private function transformArticles($articles)
    {
        // validate the response data
        if (isset($articles['status'], $articles['articles']) && $articles['status'] == 'ok' && count($articles['articles']) > 0) {
            return collect($articles['articles'])->map(function ($article) {
                return [
                    'title' => $article['title'],
                    'author' => $article['author'] ?? 'Unknown Author',
                    'description' => $article['description'] ?? null,
                    'content' => $article['content'] ?? null,
                    'url' => $article['url'],
                    'image' => $article['urlToImage'] ?? null,
                    'published_at' => Carbon\Carbon::parse($article['publishedAt'])->format('Y-m-d H:m:s'),
                ];
            })->toArray();
        }

        // Return an empty array
        return [];
    }
}

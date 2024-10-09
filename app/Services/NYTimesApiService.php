<?php

namespace App\Services;

use Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NYTimesApiService
{
    private $apiKey;

    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('NY_TIMES_API_SECRET_KEY');
        $this->baseUrl = 'https://api.nytimes.com/svc/search/v2/';
        // Check if the NY Times Api secret key is set
        if (! $this->apiKey) {
            Log::error('NY Times Api secret key not found');
            //
            throw new \Exception('NY Times Api secret key not found');
        }
    }

    /**
     * Fetch articles from the NY Times Api
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function fetchArticles($params = [])
    {
        try {
            // Fetch articles from the NY Times Api
            $response = Http::get($this->baseUrl.'articlesearch.json', array_merge([
                'api-key' => $this->apiKey,
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
     * Transform the articles from the NY Times Api
     *
     * @param  array<string, mixed>  $articles
     * @return array<string, mixed>
     */
    private function transformArticles($articles)
    {
        // validate the response data
        if (isset($articles['status'], $articles['response']) && $articles['status'] == 'OK' && count($articles['response']['docs']) > 0) {
            return collect($articles['response']['docs'])->map(function ($article) {
                return [
                    'title' => $article['headline']['main'],
                    'author' => $article['byline']['original'] ?? 'Unknown Author',
                    'description' => $article['snippet'] ?? null,
                    'content' => $article['lead_paragraph'] ?? null,
                    'url' => $article['web_url'],
                    'image' => $article['multimedia'][0]['url'] ?? null,
                    'published_at' => Carbon\Carbon::parse($article['pub_date'])->format('Y-m-d H:m:s'),
                ];
            })->toArray();
        }

        // Return an empty array
        return [];
    }
}

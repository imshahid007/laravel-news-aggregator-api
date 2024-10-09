<?php

namespace App\Services;

use Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TheGuardianApiService
{
    private $apiKey;

    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('THE_GUARDIAN_API_SECRET_KEY');
        $this->baseUrl = 'https://content.guardianapis.com/';
        // Check if the Guardian api secret key is set
        if (! $this->apiKey) {
            Log::error('Guardian api secret key not found');
            //
            throw new \Exception('Guardian api secret key not found');
        }
    }

    /**
     * Fetch articles from the Guardian api
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    public function fetchArticles($params = [])
    {
        try {
            // Fetch articles from the Guardian api
            $response = Http::get($this->baseUrl.'search', array_merge([
                'api-key' => $this->apiKey,
                'page-size' => 10,
                'show-fields' => 'publication,trailText,headline',
                'show-references' => 'author',
                'show-elements' => 'image',
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
     * Transform the articles from the Guardian api
     *
     * @param  array<string, mixed>  $articles
     * @return array<string, mixed>
     */
    private function transformArticles($articles)
    {
        // validate the response data
        if (isset($articles['response']['status'], $articles['response']['results']) && $articles['response']['status'] == 'ok' && count($articles['response']['results']) > 0) {
            return collect($articles['response']['results'])->map(function ($article) {
                return [
                    'title' => $article['webTitle'],
                    'author' => $article['fields']['publication'] ?? 'Unknown Author',
                    'description' => $article['fields']['headline'] ?? null,
                    'content' => $article['fields']['trailText'] ?? null,
                    'url' => $article['webUrl'],
                    'image' => null,
                    'published_at' => Carbon\Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:m:s'),
                ];
            })->toArray();
        }

        // Return an empty array
        return [];
    }
}

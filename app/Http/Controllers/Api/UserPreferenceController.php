<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\UserPreferenceResource;
use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserPreferenceController extends Controller
{
    /**
     * Retrieve the user preferences
     *
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        //
        $preferences = UserPreference::where('user_id', $request->user()->id)->first();
        // Check if preferences exist
        if (! $preferences) {
            return response()->json(['message' => 'User Preferences not found'], 404);
        }

        //
        return UserPreferenceResource::make($preferences);
    }

    /**
     *  Create or update the user preferences
     */
    public function store(UserPreferenceRequest $request)
    {
        //
        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->validated()
        );

        //
        return UserPreferenceResource::make($preferences);
    }

    /**
     *  User personalized feed
     *
     * @return JsonResponse
     */
    public function personalizedFeed(Request $request)
    {
        // Cache duration from .env file
        $cache_duration = env('CACHE_DURATION');
        // Get the user ID
        $user_id = $request->user()->id;
        // Cache the personalized feed for 5 minutes (300 seconds)
        $feed = Cache::remember("user_{$user_id}_personalized_feed", $cache_duration, function () use ($user_id) {
            // Get user preferences
            $preferences = UserPreference::where('user_id', $user_id)->first();
            //
            if (! $preferences) {
                return response()->json([
                    'message' => 'User Preferences not found',
                ], 404);
            }

            // Initialize the query
            $query = Article::query();

            // Apply filters based on user preferences
            if ($preferences->preferred_categories) {
                $query->orWhereIn('category_id', $preferences->preferred_categories);
            }

            if ($preferences->preferred_sources) {
                $query->orWhereIn('news_source_id', $preferences->preferred_sources);
            }

            if ($preferences->preferred_authors) {
                $query->orWhereIn('author_id', $preferences->preferred_authors);
            }

            // Fetch the articles, order them by the latest published date
            $articles = $query->select('id', 'category_id', 'news_source_id', 'author_id', 'title', 'description', 'content', 'url', 'image', 'published_at')
                ->orderBy('published_at', 'desc')
                ->paginate(10);

            // Return the paginated response using the ArticleResource
            return ArticleResource::collection($articles);
        });

        // Return the cached feed
        return $feed;
    }
}

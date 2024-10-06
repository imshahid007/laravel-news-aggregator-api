<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsSourceResource;
use App\Models\NewsSource;
use Illuminate\Http\Request;

class NewsSourceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Fetch latest news sources and paginate them
        return NewsSourceResource::collection(NewsSource::latest()->paginate(10));
    }
}

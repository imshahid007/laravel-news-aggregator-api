<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ArticleAggregatorService;

class TestController extends Controller
{
    //

    public function index(ArticleAggregatorService $articleAggregatorService)
    {
        $articles = $articleAggregatorService->aggregateArticles();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __invoke()
    {
        // Fetch latest categories and paginate them
        return CategoryResource::collection(Category::latest()->paginate(10));
    }
}

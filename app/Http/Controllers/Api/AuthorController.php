<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Http\Resources\AuthorResource;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        // Fetch latest categories and paginate them
        return AuthorResource::collection(Author::select('id', 'name', 'slug')->latest()->paginate(10));
    }
}

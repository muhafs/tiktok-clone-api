<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllPostsCollection;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::orderBy('created_at', 'desc')->get();

            return response()->json(new AllPostsCollection($posts), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

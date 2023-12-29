<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a paginated list of all articles.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $posts = Post::paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List of all articles',
            'data' => $posts,
        ], 200);
    }
}

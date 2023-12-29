<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'title' => ['required', 'max:255'],
                'slug' => ['required', 'unique:posts'],
                'content' => ['required'],
                'image' => ['image', 'max:2048'],
                'category_id' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors()
                ], 422);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $imagePath = $image->storeAs('images', $imageName, 'public');
            }

            $postData = [
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'content' => $request->input('content'),
                'image' => $imagePath,
                'user_id' => auth()->user()->id,
                'category_id' => $request->input('category_id'),
            ];

            Post::create($postData);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data Berhasil Disimpan!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            return response()->json(['success' => false, 'message' => 'Data Gagal Disimpan!', 'errors' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, Post $post): JsonResponse
    {
        return $post ?
            response()->json(['success' => true, 'message' => 'Detail Artikel!', 'data' => $post], 200) :
            response()->json(['success' => false, 'message' => 'Artikel Tidak Ditemukan!', 'data' => ''], 404);
    }
}

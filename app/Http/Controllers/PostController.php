<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'message' => 'success',
            'data' => $posts
        ]);
    }


    public function store(PostRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts/images','public');
            $imageUrl = "/storage/{$path}";
            $request->merge(['image_url' => $imageUrl]);
        }
        
        $post = Post::create($request->all());

        return response()->json([
            'message' => 'success',
            'data' => $post
        ], 201);
    }
}

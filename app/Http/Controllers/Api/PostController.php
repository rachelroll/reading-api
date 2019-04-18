<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;

class PostController extends Controller
{
    // ËùÓĞÆÀÂÛ
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        return PostResource::collection($posts);
        //return [
        //    'posts' => $posts
        //];
    }

    public function store(Request $request)
    {
        Post::create([
            'book_name' => $request->input('book_name', ''),
            'content' => $request->input('post_content', ''),
            'cover' => $request->input('cover', ''),
            'user_id' => $request->input('user_id', 0),
            'user_nickname' => $request->input('user_nickname', ''),
        ]);
    }
}

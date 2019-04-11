<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // кЫспфюбш
    public function index()
    {
        $posts = Post::all();

        return new \App\Http\Resources\Post($posts);
    }
}

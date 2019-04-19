<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    // ��������
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        return PostResource::collection($posts);
    }

    public function store(Request $request)
    {
        $file = $request->file('image', '');
        $cover = $this->upload($file, 500);

        $token = $request->token;

        // �� Redis ��ȡ���û� ID
        $user_id = Redis::get($token);

        // �ж� token �Ƿ����
        if (!$user_id) {
            return [
                'code' => 202,
                'msg' => 'token expires'
            ];
        }

        Post::create([
            'book_name' => $request->input('book_name', ''),
            'content' => $request->input('post_content', ''),
            'cover' => $cover,
            'user_id' => $user_id,
            'user_nickname' => $request->input('user_nickname', ''),
        ]);

        return [
            'code' => 200,
            'msg' => 'success'
        ];
    }
}

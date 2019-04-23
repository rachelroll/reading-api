<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Support\Facades\Redis;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $post_id = $request->id;

        $comments = Comment::where('post_id', $post_id)->get();

        foreach ($comments as &$comment) {
            $comment->time = Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans();
        }
        return CommentResource::collection($comments);
    }

    public function store(Request $request)
    {
        $token = $request->token;
        // 从 Redis 中取出用户 ID
        $user_id = Redis::get($token);
        // 判断 token 是否过期
        if (!$user_id) {
            return [
                'code' => 202,
                'msg' => 'token expires'
            ];
        }

        Comment::create([
            'user_id' => $user_id,
            'post_id' => $request->post_id,
            'content' => $request->comment,
            'user_nickname' => $request->user_nickname,
            'user_avatar' => $request->user_avatar
        ]);

        return [
            'code' => 200,
            'msg'  => 'success',
        ];
    }
}

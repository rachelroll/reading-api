<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // 所有评论
    public function index()
    {
        $posts = Post::withCount('comments')->orderBy('likes', 'desc')->get();

        foreach ($posts as &$post) {
            $post->cover = config('edu.cdn_domain').'/'.$post->cover;
            $post->summary = mb_strcut($post->content, 0, 50,'utf-8');
            $user_id = $post->user_id;
            $post->user_avatar = optional(User::where('id', $user_id)->first())->avatar;
        }
        return PostResource::collection($posts);
    }

    public function store(Request $request)
    {
        $file = $request->file('image', '');
        $cover = $this->upload($file, 500);

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

    public function qrcode(Request $request)
    {
        $res = $this->getToken();

        $access_token = $res->access_token;
$url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;

// get cURL resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, $url);

// set method
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

// return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
]);

// json body
$json_array = [
    'scene' => '1234'
];
$body = json_encode($json_array);

// set body
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// send the request and save response to $response
$response = curl_exec($ch);

// stop if fails
if (!$response) {
    die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
}

//echo 'HTTP Status Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;
//echo 'Response Body: ' . $response . PHP_EOL;

// close curl resource to free up system resources
curl_close($ch);

        $filename = "qrcode.png";
        $bool = Storage::disk('oss')->put("qrcode.png", $response);
        //file_put_contents("qrcode.png", $response);
        if ($bool) {
            //$base64_image ="data:image/jpeg;base64,".base64_encode( $response );
            return config('edu.cdn_domain').'/'.$filename;

        }
    }

    // 点赞
    public function like(Request $request)
    {
        $id = $request->id;

        $post = Post::where('id', $id)->first();

        $post->likes += 1;

        $post->save();

        return [
            'code' => 200,
            'msg' => 'update success'
        ];
    }

    // 我的所有书评
    public function myPost(Request $request)
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
        $posts = Post::withCount('comments')->where('user_id', $user_id)->get();

        foreach ($posts as &$post) {
            $post->cover = config('edu.cdn_domain').'/'.$post->cover;
            $post->summary = mb_strcut($post->content, 0, 50,'utf-8');
        }

        return PostResource::collection($posts);
    }

    // 书评详情页
    public function show(Request $request)
    {
        $post_id = $request->id;

        $post = Post::withCount('comments')->where('id', $post_id)->first();

        $user_id = $post->user_id;

        $user = User::where('id', $user_id)->first();

        $post->user_avatar = $user->avatar;

        $post->cover = config('edu.cdn_domain').'/'.$post->cover;

        $post->time = Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans();

        return new PostResource($post);
    }

    // 根据书名搜索书评
    public function search(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $posts = Post::withCount('comments')->where('book_name', 'LIKE', "%$search%")->get();
            //$posts = Post::where('book_name', 'like', $search)->get();
            if (!empty($posts)) {

                foreach($posts as &$post) {
                    $post->cover = config('edu.cdn_domain').'/'.$post->cover;
                    $post->summary = mb_strcut($post->content, 0, 50,'utf-8');
                    $user_id = $post->user_id;
                    $post->user_avatar = optional(User::where('id', $user_id)->first())->avatar;
                }
                return PostResource::collection($posts);
            } else{
                return [
                    'code' => 404,
                    'msg' => '没有搜索到'
                ];
            }
        } else {
            return [
                'code' => 202,
                'msg' => '不知道要搜索什么'
            ];
        }
    }
}

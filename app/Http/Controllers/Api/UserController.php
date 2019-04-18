<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Utils\Utils;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Psy\Util\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return UserResource::collection($users);
    }

    public function info(Request $request)
    {
        // 声明CODE，获取小程序传过来的CODE
        $code = $request->code;

        //配置appid
        $appid = env('APPID');
        //配置appscret
        $secret = env('APPSECRET');
        //api接口
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

        $res = Utils::curl($api);

        $res = json_decode($res);

        $openid = $res->openid;
        $session_key = $res->session_key;

        // 把 session_key 和 openid 存入数据库, 并返回用户 id
        $id = DB::table('users')->insertGetId(
            ['session_key' => $session_key,
             'openid' => $openid]
        );

        if ($id) {
            // 把用户 ID 加密生成 token
            $token = md5($id, config('salt'));

            Redis::setex($token, 7200, $id); // 存入 session
            return $token;
        } else {
            return [
               'code' => 202,
               'msg' => 'error'
            ];
        }
    }

    public function store(Request $request)
    {
        $nickname = $request->nickname;
        $avatar = $request->avatar;
        $token = $request->token;

        // 从 Redis 中取出用户 ID
        $id = Redis::get($token);

        // 判断 token 是否过期
        if (!$id) {
            return [
                'code' => 202,
                'msg' => 'token expires'
            ];
        }

        // 根据 ID 找到这个用户, 完善用户信息
        DB::table('users')
            ->where('id', $id)
            ->update(['nickname' => $nickname, 'avatar' => $avatar]);

        return [
            'code' => 200,
            'msg' => 'success'
        ];
    }
}






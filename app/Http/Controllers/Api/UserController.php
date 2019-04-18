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
        // ����CODE����ȡС���򴫹�����CODE
        $code = $request->code;

        //����appid
        $appid = env('APPID');
        //����appscret
        $secret = env('APPSECRET');
        //api�ӿ�
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

        $res = Utils::curl($api);

        $res = json_decode($res);

        $openid = $res->openid;
        $session_key = $res->session_key;

        // �� session_key �� openid �������ݿ�, �������û� id
        $id = DB::table('users')->insertGetId(
            ['session_key' => $session_key,
             'openid' => $openid]
        );

        if ($id) {
            // ���û� ID �������� token
            $token = md5($id, config('salt'));

            Redis::setex($token, 7200, $id); // ���� session
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

        // �� Redis ��ȡ���û� ID
        $id = Redis::get($token);

        // �ж� token �Ƿ����
        if (!$id) {
            return [
                'code' => 202,
                'msg' => 'token expires'
            ];
        }

        // ���� ID �ҵ�����û�, �����û���Ϣ
        DB::table('users')
            ->where('id', $id)
            ->update(['nickname' => $nickname, 'avatar' => $avatar]);

        return [
            'code' => 200,
            'msg' => 'success'
        ];
    }
}






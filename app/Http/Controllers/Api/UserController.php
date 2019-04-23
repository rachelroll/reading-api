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
        $users = User::withCount('posts')->get();

        //foreach($users as &$user) {
        //    $user->avatar = config('edu.cdn_domain').'/'.$user->avatar;
        //}

        return UserResource::collection($users);
    }

    // ����΢�Žӿڻ�ȡ�û� openid
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

        // �ҵ� openid
        //$openid ="ofm0N5N2XB99Fo-xEuFNwPkk-Fys";

        // ���� openid ���û������Ƿ�������û�
        $user_id = optional(User::where('openid', $openid)->first())->id;
        if ($user_id) {
            // ���û� ID �������� token
            $token = md5($user_id, config('salt'));
            Redis::set($token, $user_id); // ���� session
            Redis::expire($token, 7200); // ���ù���ʱ��

            return $token;
        }
        else{
            // �� session_key �� openid �������ݿ�, �������û� id
            $id = DB::table('users')->insertGetId(
                ['session_key' => $session_key,
                 'openid' => $openid]
            );
             // ����û�����ɹ�
            if ($id) {
                // ���û� ID �������� token
                $token = md5($id, config('salt'));

                Redis::set($token, 7200, $id); // ���� session
                Redis::expire($token, 7200);
                return $token;
            }else {
                return [
                    'code' => 202,
                    'msg' => 'error'
                ];
            }
        }
    }

    // �����û���Ϣ
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

    // ��ȡ��ǰ�û���Ϣ
    public function userInfo(Request $request)
    {
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
        $user = User::withCount('posts')->where('id', $id)->first();

        return new UserResource($user);
    }
}






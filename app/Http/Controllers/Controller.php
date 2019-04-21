<?php

namespace App\Http\Controllers;

use App\Utils\Utils;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function upload($file, $size)
    {
        $image = Image::make($file->getRealPath())->fit($size)->encode('jpg');
        $filename = 'files/' . date('Y-m-d-h-i-s') . '-' . $file->getClientOriginalName();
        $bool = Storage::disk('oss')->put($filename, $image->__toString());
        if ($bool) {
            return $filename;
        } else {
            return '';
        }
    }

    protected function getToken()
    {
        //配置appid
        $appid = env('APPID');
        //配置appscret
        $secret = env('APPSECRET');
        // 获取 access_token 的 api接口
        $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";

        $res = Utils::curl($api);

        $res = json_decode($res);

        return $res;

    }
}

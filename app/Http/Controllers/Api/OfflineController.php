<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Offline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\Offline as OfflineResource;

class OfflineController extends Controller
{
    // 提交线下读书会信息
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

        $city = json_encode($request->city);

        if($request->subject == Offline::SALON) {
            $subject = 1;
        } elseif($request->subject == Offline::TRAIN) {
            $subject = 2;
        } else {
            $subject = 3;
        }

        Offline::create([
            'title' => $request->title,
            'company' => $request->company,
            'date' => $request->date,
            'city' => $city,
            'address' => $request->address,
            'contact' => $request->contact,
            'phone' => $request->phone,
            'email' => $request->email,
            'subject' => $subject,
            'user_id' => $user_id,
            'cover' => $cover
        ]);

        return [
            'code' => 200,
            'msg'  => 'success',
        ];
    }

    public function index(Request $request)
    {
        $category_id = $request->id;

        if ($category_id) {
            $meetings = Offline::where('subject', $category_id)->get();
        } else{
            $meetings = Offline::orderBy('created_at', 'desc')->get();
        }

        foreach ($meetings as $meeting) {
            $meeting->category_id = $meeting->subject;
            $meeting->subject = Offline::CATEGORY[$category_id];
            $meeting->cover = 'https:'.config('edu.cdn_domain').'/'.$meeting->cover;
        }


        return OfflineResource::collection($meetings);
    }

    public function show(Request $request)
    {
        $id = $request->id;

        $meeting = Offline::where('id', $id)->first();

        $category_id = $meeting->subject;
        $meeting->subject = Offline::CATEGORY[$category_id];

        $meeting->cover = 'https:'.config('edu.cdn_domain').'/'.$meeting->cover;

        return new OfflineResource($meeting);
    }
}

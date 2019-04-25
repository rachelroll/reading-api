<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Offline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\Offline as OfflineResource;

class OfflineController extends Controller
{
    // 提交线下读书会信息
    public function store(Request $request)
    {
        $file = $request->file('image', '');
        $cover = $this->upload($file, 500);

        Log::info($cover);

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
        Log::info(1);
        $city = json_encode($request->city);

        if($request->subject == Offline::SALON) {
            $subject = 1;
        } elseif($request->subject == Offline::TRAIN) {
            $subject = 2;
        } else {
            $subject = 3;
        }
        Log::info(1);
        Offline::create([
            'title' => $request->title,
            'company' => $request->company,
            'date' => $request->date,
            'time' => $request->time,
            'city' => $city,
            'address' => $request->address,
            'contact' => $request->contact,
            'phone' => $request->phone,
            'email' => $request->email,
            'subject' => $subject,
            'description' => $request->description,
            'user_id' => $user_id,
            'cover' => $cover
        ]);

        return [
            'code' => 200,
            'msg'  => 'success',
        ];
    }

    public function category(Request $request)
    {
        $category_id = $request->id;
        // 按分类查询
        if ($category_id) {
            $meetings = Offline::where('subject', $category_id)->get();
        } else{
            // 查询所有数据
            $meetings = Offline::all();

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

    public function index()
    {
        $meetings = Offline::all();
        foreach($meetings as $meeting) {
            $dt = Carbon::createFromDate($meeting->date);
            $month = $dt->month;
            $day = $dt->day;

            $array = [
                $month => [
                    $day => [
                        'time' => $meeting->time,
                        'title' => $meeting->title,
                        'position' => $meeting->city,
                        'desc' => $meeting->descripition,
                    ]
                ]
            ];
        }
        return $array;
    }
}

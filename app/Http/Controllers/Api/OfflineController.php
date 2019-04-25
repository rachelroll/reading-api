<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Offline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                'msg'  => 'token expires',
            ];
        }
        Log::info(1);
        $city = json_encode($request->city);

        if ($request->subject == Offline::SALON) {
            $subject = 1;
        } elseif ($request->subject == Offline::TRAIN) {
            $subject = 2;
        } else {
            $subject = 3;
        }
        Log::info(1);
        Offline::create([
            'title'       => $request->title,
            'company'     => $request->company,
            'date'        => $request->date,
            'time'        => $request->time,
            'city'        => $city,
            'address'     => $request->address,
            'contact'     => $request->contact,
            'phone'       => $request->phone,
            'email'       => $request->email,
            'subject'     => $subject,
            'description' => $request->description,
            'user_id'     => $user_id,
            'cover'       => $cover,
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
        } else {
            // 查询所有数据
            $meetings = Offline::all();

        }

        foreach ($meetings as $meeting) {
            $meeting->category_id = $meeting->subject;
            $meeting->subject = Offline::CATEGORY[ $category_id ];
            $meeting->cover = 'https:' . config('edu.cdn_domain') . '/' . $meeting->cover;
        }

        return OfflineResource::collection($meetings);
    }

    public function show(Request $request)
    {
        $id = $request->id;

        $meeting = Offline::where('id', $id)->first();

        $category_id = $meeting->subject;
        $meeting->subject = Offline::CATEGORY[ $category_id ];

        $meeting->cover = 'https:' . config('edu.cdn_domain') . '/' . $meeting->cover;

        return new OfflineResource($meeting);
    }

    public function index()
    {
        $offlines = DB::select('select *, MONTH(date) as month,DAY(date) as day  from offlines where YEAR(date) = ?  ',
            [2019]);
        $arr = [];
        collect($offlines)->groupBy('month')->map(function ($item, $key) use (&$arr) {
            $item = collect($item)->groupBy('day');
            $tmp = [];
            $item->each(function ($it, $kk) use (&$tmp) {
                $c = [];
                $it->each(function ($i, $k) use (&$c) {
                    $time = $i->time ?? '00:00:00';
                    $time_obj = Carbon::createFromTimeString($i->date . ' ' . $time);

                    $t = [
                        'meridiem' => $time_obj->format('A') == 'AM' ? '上午' : '下午',
                        'time'     => $time_obj->format('H:i:s'),
                        'title'    => $i->subject,
                        'position' => $i->address,
                        'desc'     => $i->description,
                    ];
                    $c[] = $t;
                });

                $tmp[ $kk ] = $c;

            });
            $arr[ $key ] = $tmp;

        });

        return $arr;


    }
}

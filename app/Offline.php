<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offline extends Model
{

    const SALON = 1;
    const TRAIN = 2;
    const COURSE = 3;

    const CATEGORY = [
        self::SALON => '读书沙龙',
        self::TRAIN => '专题讲座',
        self::COURSE => '技能培训',
    ];

    protected $fillable = [
        'title', 'company', 'date', 'time','city', 'address', 'contact', 'phone', 'email', 'subject', 'user_id', 'description', 'cover'
    ];
}

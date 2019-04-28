<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $age = Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(null,true);
        return [
            'nickname' => $this->nickname,
            'avatar' => $this->avatar,
            'book_name' => $this->book_name,
            'post_id' => $this->post_id,
            'age' => $age,
            'posts_count' => $this->posts_count,
            'comments_count' => $this->comments_count
        ];
    }
}

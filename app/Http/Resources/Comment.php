<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'content' => $this->content,
            'post_id' => $this->post_id,
            'user_nickname' => $this->user_nickname,
            'user_avatar' => $this->user_avatar,
            'time' => $this->time
        ];
    }
}

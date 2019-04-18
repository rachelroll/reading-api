<?php

namespace App\Http\Resources;

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
        return [
            'nickname' => $this->nickname,
            'avatar' => $this->avatar,
            'book_name' => $this->book_name,
            'post_id' => $this->post_id,
            'openid' => $this->openid
        ];
    }
}

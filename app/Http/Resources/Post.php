<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
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
            'content' => $this->content,
            'book_name' => $this->bookname,
            'cover' => $this->cover,
            'user_id' => $this->user_id,
            'user_nickname' => $this->user_nickname,
            'likes' => $this->likes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

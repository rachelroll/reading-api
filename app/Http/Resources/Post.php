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
            'id' => $this->id,
            'content' => $this->content,
            'book_name' => $this->book_name,
            'cover' => $this->cover,
            'user_id' => $this->user_id,
            'user_nickname' => $this->user_nickname,
            'likes' => $this->likes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'summary' => $this->summary,
            'comments_count' => $this->comments_count,
            'avatar' => $this->user_avatar,
            'time' => $this->time
        ];
    }
}

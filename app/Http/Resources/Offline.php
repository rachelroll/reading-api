<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Offline extends JsonResource
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
            'company' => $this->company,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'city' => $this->city,
            'address' => $this->address,
            'contact' => $this->contact,
            'phone' => $this->phone,
            'email' => $this->email,
            'subject' => $this->subject,
            'user_id' => $this->user_id,
            'cover' => $this->cover,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                'email' => $this->email,
                'name' => $this->name,
                'password' => $this->password,
                'nim' => $this->nim,
                'role_id' => $this->role_id,
                'division' => $this->division,
				'email_verified_at' => $this->email_verified_at,
            ];
    }
}

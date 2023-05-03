<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
			'id' => $this->id,
			'info' => env('APP_URL'). 'laravel/storage/app/public/info/'.  $this->info,
		];
    }
}

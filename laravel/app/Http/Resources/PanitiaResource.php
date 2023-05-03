<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PanitiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
		if($this->vaccine_certificate != 'none') {
			$imgLink = env('APP_URL'). 'laravel/storage/app/public/vaccine_image/'.  $this->vaccine_certificate;		
		}
		else {
			$imgLink = $this->vaccine_certificate;
		}
		
        return
            [
                'id' => $this->id,
                'nim' => $this->nim,
                'name' => $this->name,
                'email' => $this->email,
                'program_studi' => $this->program_studi,
                'vaccine_certificate' => $imgLink,
                'angkatan' => $this->angkatan,
                'division_1' => $this->division_1,
                'division_2' => $this->division_2,
                'phone_number' => $this->phone_number,
                'reason_1' => $this->reason_1,
                'reason_2' => $this->reason_2,
                'portofolio' => $this->portofolio,
                'id_line' => $this->id_line,
                'instagram_account' => $this->instagram_account,
                'city' => $this->city,
                'is_accepted' => $this->is_accepted,
				'created_at' => \Carbon\Carbon::parse($this->created_at)->format('j F Y h:i:s A'),
            ];
    }
}

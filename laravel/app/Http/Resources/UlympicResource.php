<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UlympicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
		$buktiPembayaran = $this->buktiPembayaran;
     
        if ($buktiPembayaran != null) {
           $buktiPembayaran = env('APP_URL'). 'laravel/storage/app/public/bukti_pembayaran/'.  $this->buktiPembayaran;
        } else  $buktiPembayaran = "none";
		
        
		return
            [
                'id' => $this->id,
                'namaTim' => $this->namaTim,
				'ketua' => $this->ketua,
                'buktiPembayaran' => $buktiPembayaran,
				'jumlahMember' => $this->jumlahMember,
				'tokenID' => $this->tokenID,
            ];
    }
}

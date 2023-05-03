<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimMobileLegendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $buktiWA = $this->buktiWA;
        $fotoKtm = $this->fotoKtm;

        if ($buktiWA != null) {
           $buktiWA = env('APP_URL'). 'laravel/storage/app/public/bukti_WA/'.  $this->buktiWA;
        } else  $buktiWA = "none";

        if ($fotoKtm != null) {
            $fotoKtm = env('APP_URL'). 'laravel/storage/app/public/foto_ktm/'.  $this->fotoKtm;
        } else $fotoKtm = "none";
		
       return  [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'ketua' => $this->ketua,
            'nama' => $this->nama,
            'jurusan' => $this->jurusan,
            'angkatan' => $this->angkatan,
            'userID' => $this->userID,
            'username' => $this->userName,
            'phoneNumber' => $this->phoneNumber,
		    'buktiWA' => $buktiWA,
		    'fotoKtm' => $fotoKtm,
            'diterima' => $this->diterima,
            'tokenID' => $this->tokenID,
        ];
    }
}

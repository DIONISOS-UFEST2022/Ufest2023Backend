<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimMobileLegend extends Model
{
    use HasFactory;

    protected $fillable = [
        'ketua',
        'tokenID',
        'nama',
        'buktiWA',
        'fotoKtm',
        'jurusan',
        'angkatan',
        'userID',
        'userName',
        'phoneNumber',
        'diterima',
    ];

    public function ulympic()
    {
        return $this->belongsTo(Ulympic::class, 'tokenID', 'tokenID');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulympic extends Model
{
    use HasFactory;

    public $table = "ulympics";

    protected $fillable = [
        'namaTim',
        'ketua',
        'buktiPembayaran',
        'jumlahMember',
        'tokenID',
    ];

    public function timMobileLegend()
    {
        return $this->hasMany(TimMobileLegend::class, 'tokenID', 'tokenID');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class panitia extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "panitia";

    protected $fillable = [
        'nim',
        'name',
        'email',
        'program_studi',
        'angkatan',
        'vaccine_certificate',
        'division_1',
        'division_2',
        'phone_number',
        'reason_1',
        'reason_2',
        'portofolio',
        'id_line',
        'instagram_account',
        'city',
        'is_accepted',
    ];
}

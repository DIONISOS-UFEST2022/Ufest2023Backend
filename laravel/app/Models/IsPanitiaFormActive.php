<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsPanitiaFormActive extends Model
{
    use HasFactory;

    public $table = "is_panitia_form_active";

    protected $fillable = [
        'isActive',
    ];
}

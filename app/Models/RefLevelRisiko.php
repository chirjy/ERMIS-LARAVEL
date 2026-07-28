<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefLevelRisiko extends Model
{
    protected $fillable = [
        'rentang_min', 'rentang_max', 'label', 'simbol_warna', 'warna_hex',
        'tindakan', 'wajib_pengujian_pengendalian',
    ];

    protected $casts = [
        'wajib_pengujian_pengendalian' => 'boolean',
    ];
}

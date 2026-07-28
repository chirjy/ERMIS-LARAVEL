<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefKategoriRisiko extends Model
{
    protected $fillable = [
        'kode', 'nama', 'prioritas', 'penjelasan',
        'contoh_kasus', 'area_dampak_lazim', 'kata_kunci_identifikasi',
    ];
}

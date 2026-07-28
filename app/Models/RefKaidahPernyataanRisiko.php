<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefKaidahPernyataanRisiko extends Model
{
    protected $fillable = [
        'kode_kaidah', 'judul', 'deskripsi_kaidah', 'contoh_benar', 'contoh_salah',
        'tipe_pemeriksaan', 'parameter_pemeriksaan', 'tingkat_pelanggaran',
    ];

    protected $casts = [
        'parameter_pemeriksaan' => 'array',
    ];
}

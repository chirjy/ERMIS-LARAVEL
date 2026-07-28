<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefKriteriaKemungkinan extends Model
{
    protected $fillable = [
        'level', 'label', 'probabilitas_min_persen', 'probabilitas_max_persen',
        'kriteria_jumlah_frekuensi_non_low', 'kriteria_frekuensi_low', 'contoh_kejadian',
    ];
}

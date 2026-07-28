<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefKriteriaDampak extends Model
{
    protected $fillable = [
        'area_dampak_id', 'tingkatan_upr', 'level', 'label_level', 'deskripsi_kriteria', 'parameter_kuantitatif',
    ];

    protected $casts = [
        'parameter_kuantitatif' => 'array',
    ];

    public function areaDampak()
    {
        return $this->belongsTo(RefAreaDampak::class, 'area_dampak_id');
    }
}

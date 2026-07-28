<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TrnAnalisisRisiko extends Model
{
    use HasUuids;

    protected $fillable = [
        'identifikasi_risiko_id', 'area_dampak_id',
        'level_kemungkinan_inheren', 'level_dampak_inheren', 'level_risiko_inheren',
        'metode_penentuan_kemungkinan_id', 'metode_penentuan_dampak_id',
        'uraian_dasar_pertimbangan', 'pendekatan_kemungkinan',
        'aktivitas_pengendalian', 'atribut_pengendalian',
        'penilaian_kelemahan_pengendalian', 'simpulan_efektivitas_pengendalian',
        'level_kemungkinan_residual', 'level_dampak_residual', 'level_risiko_residual',
        'is_top_risk', 'urutan_prioritas', 'created_by',
    ];

    protected $casts = [
        'is_top_risk' => 'boolean',
    ];

    public function identifikasiRisiko()
    {
        return $this->belongsTo(TrnIdentifikasiRisiko::class, 'identifikasi_risiko_id');
    }

    public function areaDampak()
    {
        return $this->belongsTo(RefAreaDampak::class, 'area_dampak_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(SysUser::class, 'created_by');
    }

    public function rtps()
    {
        return $this->hasMany(TrnRencanaTindakPengendalian::class, 'analisis_risiko_id');
    }

    public function metodePenentuanKemungkinan()
    {
        return $this->belongsTo(RefMetodePenilaian::class, 'metode_penentuan_kemungkinan_id');
    }

    public function metodePenentuanDampak()
    {
        return $this->belongsTo(RefMetodePenilaian::class, 'metode_penentuan_dampak_id');
    }

    public function dokumenDukung()
    {
        return $this->hasMany(TrnDokumenDukungAnalisis::class, 'analisis_risiko_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrnIdentifikasiRisiko extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'upt_id', 'tahun_anggaran', 'konteks_id',
        'sasaran_strategis', 'indikator_kinerja', 'isu', 'kegiatan_proses_bisnis',
        'kode_risiko', 'kategori_risiko_id', 'jenis_risiko',
        'pernyataan_risiko', 'penyebab_risiko', 'sumber_risiko', 'dampak_risiko',
        'pemilik_risiko_id', 'pihak_terkait',
        'status', 'catatan_penolakan', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'pihak_terkait' => 'array',
        'direviu_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_DIAJUKAN_REVIU_LINI2 = 'DIAJUKAN_REVIU_LINI2';
    public const STATUS_DITOLAK_LINI2 = 'DITOLAK_LINI2';
    public const STATUS_DIREVIU_LINI2 = 'DIREVIU_LINI2';
    public const STATUS_DITOLAK_KEPALA_UPT = 'DITOLAK_KEPALA_UPT';
    public const STATUS_DISETUJUI_KEPALA_UPT = 'DISETUJUI_KEPALA_UPT';
    public const STATUS_DIESKALASI_LINI3 = 'DIESKALASI_LINI3';

    public const EDITABLE_STATUSES = [
        self::STATUS_DRAFT, self::STATUS_DITOLAK_LINI2, self::STATUS_DITOLAK_KEPALA_UPT,
    ];

    public function upt()
    {
        return $this->belongsTo(SysUpt::class, 'upt_id');
    }

    public function konteks()
    {
        return $this->belongsTo(TrnKonteksOrganisasi::class, 'konteks_id');
    }

    public function kategoriRisiko()
    {
        return $this->belongsTo(RefKategoriRisiko::class, 'kategori_risiko_id');
    }

    public function pemilikRisiko()
    {
        return $this->belongsTo(SysUser::class, 'pemilik_risiko_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(SysUser::class, 'created_by');
    }

    public function direviuOleh()
    {
        return $this->belongsTo(SysUser::class, 'direviu_oleh');
    }

    public function approvedBy()
    {
        return $this->belongsTo(SysUser::class, 'approved_by');
    }

    public function analisisRisikos()
    {
        return $this->hasMany(TrnAnalisisRisiko::class, 'identifikasi_risiko_id');
    }

    /** Analisis risiko paling baru — dipakai utk badge level di daftar risiko */
    public function analisisTerakhir()
    {
        return $this->hasOne(TrnAnalisisRisiko::class, 'identifikasi_risiko_id')->latestOfMany();
    }
}

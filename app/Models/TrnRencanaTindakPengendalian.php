<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrnRencanaTindakPengendalian extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'trn_rencana_tindak_pengendalians';

    protected $fillable = [
        'analisis_risiko_id', 'upt_id',
        'opsi_respon_risiko', 'uraian_mitigasi', 'output_target', 'pic',
        'sumber_daya_dibutuhkan', 'target_waktu_penyelesaian',
        'kemungkinan_mitigasi', 'dampak_mitigasi', 'level_risiko_mitigasi',
        'status', 'catatan_penolakan', 'created_by',
    ];

    protected $casts = [
        'target_waktu_penyelesaian' => 'date',
        'direviu_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // State machine identik dengan Konteks Organisasi / Identifikasi Risiko,
    // hanya beda pada satu kolom signature_hash (bukan lini1/lini2 terpisah).
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

    public function analisisRisiko()
    {
        return $this->belongsTo(TrnAnalisisRisiko::class, 'analisis_risiko_id');
    }

    public function upt()
    {
        return $this->belongsTo(SysUpt::class, 'upt_id');
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

    public function pemantauans()
    {
        return $this->hasMany(TrnPemantauanReviu::class, 'rtp_id');
    }

    public function pemantauanTerakhir()
    {
        return $this->hasOne(TrnPemantauanReviu::class, 'rtp_id')->latestOfMany('tanggal_progress');
    }
}

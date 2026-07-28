<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrnKonteksOrganisasi extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'upt_id', 'tahun_anggaran', 'ruang_lingkup', 'sasaran_organisasi',
        'stakeholder', 'peraturan_terkait', 'kriteria_kemungkinan_custom', 'kriteria_dampak_custom',
        'status', 'catatan_penolakan', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'stakeholder' => 'array',
        'peraturan_terkait' => 'array',
        'direviu_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Status yang tersedia untuk state machine 3 lini (Bagian B, Revisi 2)
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

    public function identifikasiRisikos()
    {
        return $this->hasMany(TrnIdentifikasiRisiko::class, 'konteks_id');
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
}

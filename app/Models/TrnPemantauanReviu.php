<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TrnPemantauanReviu extends Model
{
    use HasUuids;

    protected $table = 'trn_pemantauan_revius';

    protected $fillable = [
        'rtp_id', 'uraian_target', 'due_date', 'pic', 'progress_persen', 'tanggal_progress',
        'penilaian_kelemahan_pengendalian', 'simpulan_efektivitas_pengendalian', 'dilaporkan_oleh',
    ];

    protected $casts = [
        'due_date' => 'date',
        'tanggal_progress' => 'date',
        'progress_persen' => 'decimal:2',
    ];

    public function rtp()
    {
        return $this->belongsTo(TrnRencanaTindakPengendalian::class, 'rtp_id');
    }

    public function dilaporkanOleh()
    {
        return $this->belongsTo(SysUser::class, 'dilaporkan_oleh');
    }
}

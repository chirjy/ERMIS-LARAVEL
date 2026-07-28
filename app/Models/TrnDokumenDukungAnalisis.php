<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrnDokumenDukungAnalisis extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'trn_dokumen_dukung_analisis';

    protected $fillable = [
        'analisis_risiko_id', 'jenis_dukungan', 'digunakan_untuk',
        'nama_file', 'path_file', 'mime_type', 'ukuran_bytes', 'keterangan', 'diunggah_oleh',
    ];

    public function analisisRisiko()
    {
        return $this->belongsTo(TrnAnalisisRisiko::class, 'analisis_risiko_id');
    }

    public function diunggahOleh()
    {
        return $this->belongsTo(SysUser::class, 'diunggah_oleh');
    }
}

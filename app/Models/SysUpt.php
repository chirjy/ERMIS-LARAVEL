<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysUpt extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama', 'jenjang', 'provinsi', 'aktif'];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(SysUser::class, 'upt_id');
    }
}

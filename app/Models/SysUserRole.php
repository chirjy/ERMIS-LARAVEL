<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SysUserRole extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['user_id', 'role_id', 'upt_id'];

    public function user()
    {
        return $this->belongsTo(SysUser::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(SysRole::class, 'role_id');
    }

    public function upt()
    {
        return $this->belongsTo(SysUpt::class, 'upt_id');
    }
}

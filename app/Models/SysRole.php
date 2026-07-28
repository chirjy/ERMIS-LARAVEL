<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysRole extends Model
{
    protected $fillable = ['kode', 'nama', 'lini'];

    public function userRoles()
    {
        return $this->hasMany(SysUserRole::class, 'role_id');
    }
}

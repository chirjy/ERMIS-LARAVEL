<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SysUser extends Authenticatable
{
    use HasUuids, Notifiable;

    protected $table = 'sys_users';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'upt_id', 'nip', 'nama', 'email', 'password', 'jabatan', 'aktif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'aktif' => 'boolean',
        'password' => 'hashed',
    ];

    /** Nama tampilan dipakai di navbar / audit trail */
    public function getNameAttribute(): string
    {
        return $this->nama;
    }

    public function upt()
    {
        return $this->belongsTo(SysUpt::class, 'upt_id');
    }

    public function userRoles()
    {
        return $this->hasMany(SysUserRole::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(SysRole::class, 'sys_user_roles', 'user_id', 'role_id')
            ->withPivot('upt_id')
            ->withTimestamps();
    }

    /**
     * Cek apakah user punya salah satu peran (kode role) untuk UPT tertentu,
     * atau punya peran lintas-UPT (upt_id null di sys_user_roles, mis. Inspektorat Utama).
     */
    public function hasRoleInUpt(array $kodeRoles, ?int $uptId = null): bool
    {
        return $this->userRoles()
            ->whereHas('role', fn ($q) => $q->whereIn('kode', $kodeRoles))
            ->where(function ($q) use ($uptId) {
                $q->whereNull('upt_id');
                if ($uptId !== null) {
                    $q->orWhere('upt_id', $uptId);
                }
            })
            ->exists();
    }

    public function hasRole(string $kode): bool
    {
        return $this->roles()->where('kode', $kode)->exists();
    }
}

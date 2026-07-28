<?php

namespace App\Policies;

use App\Models\SysUser;
use App\Models\TrnKonteksOrganisasi;

class KonteksOrganisasiPolicy
{
    public function view(SysUser $user, TrnKonteksOrganisasi $konteks): bool
    {
        return $user->hasRoleInUpt([
            'PENGELOLA_RISIKO', 'KEPALA_UPR', 'KETUA_TIM_SPI_UNIT_KERJA', 'AUDITOR_INTERNAL',
            'KOORDINATOR_AUDITOR_INTERNAL', 'INSPEKTUR_UTAMA', 'JF_AUDITOR', 'SEKRETARIAT_KMR',
        ], $konteks->upt_id);
    }

    public function update(SysUser $user, TrnKonteksOrganisasi $konteks): bool
    {
        return $konteks->created_by === $user->id
            && in_array($konteks->status, TrnKonteksOrganisasi::EDITABLE_STATUSES, true);
    }

    public function delete(SysUser $user, TrnKonteksOrganisasi $konteks): bool
    {
        return $this->update($user, $konteks);
    }

    public function ajukanReviu(SysUser $user, TrnKonteksOrganisasi $konteks): bool
    {
        return $konteks->created_by === $user->id
            && in_array($konteks->status, TrnKonteksOrganisasi::EDITABLE_STATUSES, true);
    }

    public function reviewLini2(SysUser $user, TrnKonteksOrganisasi $konteks): bool
    {
        return $konteks->status === TrnKonteksOrganisasi::STATUS_DIAJUKAN_REVIU_LINI2
            && $user->hasRoleInUpt(['KETUA_TIM_SPI_UNIT_KERJA', 'AUDITOR_INTERNAL'], $konteks->upt_id);
    }

    public function approveFinal(SysUser $user, TrnKonteksOrganisasi $konteks): bool
    {
        return $konteks->status === TrnKonteksOrganisasi::STATUS_DIREVIU_LINI2
            && $user->hasRoleInUpt(['KEPALA_UPR'], $konteks->upt_id);
    }
}

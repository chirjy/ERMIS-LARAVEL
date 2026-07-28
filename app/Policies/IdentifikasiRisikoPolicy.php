<?php

namespace App\Policies;

use App\Models\SysUser;
use App\Models\TrnIdentifikasiRisiko;

class IdentifikasiRisikoPolicy
{
    public function view(SysUser $user, TrnIdentifikasiRisiko $risiko): bool
    {
        return $user->hasRoleInUpt([
            'PENGELOLA_RISIKO', 'KEPALA_UPR', 'KETUA_TIM_SPI_UNIT_KERJA', 'AUDITOR_INTERNAL',
            'KOORDINATOR_AUDITOR_INTERNAL', 'INSPEKTUR_UTAMA', 'JF_AUDITOR', 'SEKRETARIAT_KMR',
        ], $risiko->upt_id);
    }

    public function update(SysUser $user, TrnIdentifikasiRisiko $risiko): bool
    {
        return $risiko->created_by === $user->id
            && in_array($risiko->status, TrnIdentifikasiRisiko::EDITABLE_STATUSES, true);
    }

    public function delete(SysUser $user, TrnIdentifikasiRisiko $risiko): bool
    {
        return $this->update($user, $risiko);
    }

    public function ajukanReviu(SysUser $user, TrnIdentifikasiRisiko $risiko): bool
    {
        return $risiko->created_by === $user->id
            && in_array($risiko->status, TrnIdentifikasiRisiko::EDITABLE_STATUSES, true);
    }

    public function reviewLini2(SysUser $user, TrnIdentifikasiRisiko $risiko): bool
    {
        return $risiko->status === TrnIdentifikasiRisiko::STATUS_DIAJUKAN_REVIU_LINI2
            && $user->hasRoleInUpt(['KETUA_TIM_SPI_UNIT_KERJA', 'AUDITOR_INTERNAL'], $risiko->upt_id);
    }

    public function approveFinal(SysUser $user, TrnIdentifikasiRisiko $risiko): bool
    {
        return $risiko->status === TrnIdentifikasiRisiko::STATUS_DIREVIU_LINI2
            && $user->hasRoleInUpt(['KEPALA_UPR'], $risiko->upt_id);
    }

    /** Analisis Risiko hanya boleh dibuat/diubah selama induk risiko belum final disetujui Kepala UPT */
    public function manageAnalisis(SysUser $user, TrnIdentifikasiRisiko $risiko): bool
    {
        return $risiko->created_by === $user->id
            && $risiko->status !== TrnIdentifikasiRisiko::STATUS_DISETUJUI_KEPALA_UPT;
    }
}

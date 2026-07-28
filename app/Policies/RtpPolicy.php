<?php

namespace App\Policies;

use App\Models\SysUser;
use App\Models\TrnAnalisisRisiko;
use App\Models\TrnRencanaTindakPengendalian;

class RtpPolicy
{
    /**
     * RTP wajib disusun untuk risiko dengan level residual (atau inheren bila
     * residual belum diisi) di atas 15 — "Risiko dengan nilai level risiko
     * lebih dari 15 maka menjadi prioritas untuk ditangani" (BAB VI.D.1.b).
     */
    public function bolehBuatRtp(SysUser $user, TrnAnalisisRisiko $analisis): bool
    {
        $level = $analisis->level_risiko_residual ?? $analisis->level_risiko_inheren;

        return $level > 15
            && $analisis->identifikasiRisiko->created_by === $user->id
            && $analisis->identifikasiRisiko->status === \App\Models\TrnIdentifikasiRisiko::STATUS_DISETUJUI_KEPALA_UPT;
    }

    public function view(SysUser $user, TrnRencanaTindakPengendalian $rtp): bool
    {
        return $user->hasRoleInUpt([
            'PENGELOLA_RISIKO', 'KEPALA_UPR', 'KETUA_TIM_SPI_UNIT_KERJA', 'AUDITOR_INTERNAL',
            'KOORDINATOR_AUDITOR_INTERNAL', 'INSPEKTUR_UTAMA', 'JF_AUDITOR', 'SEKRETARIAT_KMR',
        ], $rtp->upt_id);
    }

    public function update(SysUser $user, TrnRencanaTindakPengendalian $rtp): bool
    {
        return $rtp->created_by === $user->id
            && in_array($rtp->status, TrnRencanaTindakPengendalian::EDITABLE_STATUSES, true);
    }

    public function delete(SysUser $user, TrnRencanaTindakPengendalian $rtp): bool
    {
        return $this->update($user, $rtp);
    }

    public function ajukanReviu(SysUser $user, TrnRencanaTindakPengendalian $rtp): bool
    {
        return $rtp->created_by === $user->id
            && in_array($rtp->status, TrnRencanaTindakPengendalian::EDITABLE_STATUSES, true);
    }

    public function reviewLini2(SysUser $user, TrnRencanaTindakPengendalian $rtp): bool
    {
        return $rtp->status === TrnRencanaTindakPengendalian::STATUS_DIAJUKAN_REVIU_LINI2
            && $user->hasRoleInUpt(['KETUA_TIM_SPI_UNIT_KERJA', 'AUDITOR_INTERNAL'], $rtp->upt_id);
    }

    public function approveFinal(SysUser $user, TrnRencanaTindakPengendalian $rtp): bool
    {
        return $rtp->status === TrnRencanaTindakPengendalian::STATUS_DIREVIU_LINI2
            && $user->hasRoleInUpt(['KEPALA_UPR'], $rtp->upt_id);
    }

    /** Pemantauan hanya bisa dicatat setelah RTP disetujui Kepala UPT */
    public function catatPemantauan(SysUser $user, TrnRencanaTindakPengendalian $rtp): bool
    {
        return $rtp->status === TrnRencanaTindakPengendalian::STATUS_DISETUJUI_KEPALA_UPT
            && $user->hasRoleInUpt([
                'PENGELOLA_RISIKO', 'KEPALA_UPR', 'KETUA_TIM_SPI_UNIT_KERJA', 'AUDITOR_INTERNAL',
            ], $rtp->upt_id);
    }
}

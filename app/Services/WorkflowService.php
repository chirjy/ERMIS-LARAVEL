<?php

namespace App\Services;

use App\Models\SysUser;
use App\Models\TrnIdentifikasiRisiko;
use App\Models\TrnKonteksOrganisasi;
use App\Models\TrnRencanaTindakPengendalian;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Menangani transisi status untuk model yang memakai state machine 3 lini
 * yang identik: TrnKonteksOrganisasi, TrnIdentifikasiRisiko, dan
 * TrnRencanaTindakPengendalian (Bagian B, Revisi 2).
 *
 * DRAFT -> DIAJUKAN_REVIU_LINI2 -> (DITOLAK_LINI2 | DIREVIU_LINI2)
 *       -> (DITOLAK_KEPALA_UPT | DISETUJUI_KEPALA_UPT)
 *
 * Catatan: TrnRencanaTindakPengendalian (RTP) hanya punya SATU kolom
 * `signature_hash` (bukan signature_hash_lini1/lini2 terpisah seperti dua
 * model lainnya), sehingga hash TTD hanya diisi pada tahap approve final.
 */
class WorkflowService
{
    /** LINI 1 -> LINI 2 : Pengelola Risiko mengajukan reviu */
    public function ajukanReviuLini2(TrnKonteksOrganisasi|TrnIdentifikasiRisiko|TrnRencanaTindakPengendalian $model): void
    {
        $this->assertStatus($model, [
            $model::STATUS_DRAFT, $model::STATUS_DITOLAK_LINI2, $model::STATUS_DITOLAK_KEPALA_UPT,
        ]);

        DB::transaction(function () use ($model) {
            $model->update([
                'status' => $model::STATUS_DIAJUKAN_REVIU_LINI2,
                'catatan_penolakan' => null,
            ]);
        });
    }

    /** LINI 2 : Ketua Tim SPI Unit Kerja / Auditor Internal mereviu */
    public function reviuLini2(
        TrnKonteksOrganisasi|TrnIdentifikasiRisiko|TrnRencanaTindakPengendalian $model,
        SysUser $reviewer,
        bool $disetujui,
        ?string $catatan = null
    ): void {
        $this->assertStatus($model, [$model::STATUS_DIAJUKAN_REVIU_LINI2]);
        $this->assertRole($reviewer, ['KETUA_TIM_SPI_UNIT_KERJA', 'AUDITOR_INTERNAL'], $model->upt_id);

        DB::transaction(function () use ($model, $reviewer, $disetujui, $catatan) {
            $data = [
                'status' => $disetujui ? $model::STATUS_DIREVIU_LINI2 : $model::STATUS_DITOLAK_LINI2,
                'direviu_oleh' => $reviewer->id,
                'direviu_at' => now(),
                'catatan_penolakan' => $disetujui ? null : $catatan,
            ];

            // RTP tidak punya kolom signature_hash_lini2 terpisah (lihat catatan kelas).
            if (! ($model instanceof TrnRencanaTindakPengendalian)) {
                $data['signature_hash_lini2'] = $disetujui
                    ? hash('sha256', $model->id.$reviewer->id.now()->toIso8601String())
                    : null;
            }

            $model->update($data);
        });
    }

    /** LINI 1 : Kepala UPR/UPT memberikan persetujuan final dengan TTD digital */
    public function approveKepalaUpt(
        TrnKonteksOrganisasi|TrnIdentifikasiRisiko|TrnRencanaTindakPengendalian $model,
        SysUser $approver,
        string $userSignatureKey
    ): void {
        $this->assertStatus($model, [$model::STATUS_DIREVIU_LINI2]);
        $this->assertRole($approver, ['KEPALA_UPR'], $model->upt_id);

        DB::transaction(function () use ($model, $approver, $userSignatureKey) {
            $hash = hash('sha256', $model->id.$userSignatureKey.now()->toIso8601String());
            $signatureField = $model instanceof TrnRencanaTindakPengendalian ? 'signature_hash' : 'signature_hash_lini1';

            $model->update([
                'status' => $model::STATUS_DISETUJUI_KEPALA_UPT,
                $signatureField => $hash,
                'approved_at' => now(),
                'approved_by' => $approver->id,
            ]);
        });
    }

    public function rejectKepalaUpt(TrnKonteksOrganisasi|TrnIdentifikasiRisiko|TrnRencanaTindakPengendalian $model, string $catatan): void
    {
        $this->assertStatus($model, [$model::STATUS_DIREVIU_LINI2]);
        $model->update(['status' => $model::STATUS_DITOLAK_KEPALA_UPT, 'catatan_penolakan' => $catatan]);
    }

    private function assertStatus($model, array $allowed): void
    {
        if (! in_array($model->status, $allowed, true)) {
            throw new Exception("Transisi tidak valid dari status: {$model->status}");
        }
    }

    private function assertRole(SysUser $user, array $kodeRoles, int $uptId): void
    {
        if (! $user->hasRoleInUpt($kodeRoles, $uptId)) {
            throw new Exception('Pengguna tidak memiliki peran yang berwenang untuk aksi ini.');
        }
    }
}

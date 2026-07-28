<?php

namespace App\Services;

use App\Models\RefLevelRisiko;
use App\Models\RefMatriksRisiko;
use App\Models\TrnAnalisisRisiko;
use InvalidArgumentException;

class RiskEngineService
{
    /**
     * Hitung besaran & label level risiko berdasarkan lookup ref_matriks_risikos
     * (BAB V.D.7) — TIDAK BOLEH dihitung via level_kemungkinan * level_dampak,
     * karena Kepka sengaja memberi bobot dampak lebih besar.
     *
     * @return array{besaran_risiko:int, label:string, simbol_warna:string, warna_hex:string,
     *               wajib_pengujian_pengendalian:bool}
     */
    public function hitungLevel(int $levelKemungkinan, int $levelDampak): array
    {
        $matriks = RefMatriksRisiko::query()
            ->where('level_kemungkinan', $levelKemungkinan)
            ->where('level_dampak', $levelDampak)
            ->first();

        if (! $matriks) {
            throw new InvalidArgumentException(
                "Kombinasi kemungkinan={$levelKemungkinan} dampak={$levelDampak} tidak ditemukan di ref_matriks_risikos."
            );
        }

        $level = RefLevelRisiko::query()
            ->where('rentang_min', '<=', $matriks->besaran_risiko)
            ->where('rentang_max', '>=', $matriks->besaran_risiko)
            ->first();

        if (! $level) {
            throw new InvalidArgumentException(
                "Besaran risiko {$matriks->besaran_risiko} tidak tercakup rentang manapun di ref_level_risikos."
            );
        }

        return [
            'besaran_risiko' => $matriks->besaran_risiko,
            'label' => $level->label,
            'simbol_warna' => $level->simbol_warna,
            'warna_hex' => $level->warna_hex,
            'wajib_pengujian_pengendalian' => $level->wajib_pengujian_pengendalian,
        ];
    }

    /** Hitung & isi level_risiko_inheren pada model TrnAnalisisRisiko (belum disimpan) */
    public function isiLevelInheren(TrnAnalisisRisiko $analisis): TrnAnalisisRisiko
    {
        $hasil = $this->hitungLevel($analisis->level_kemungkinan_inheren, $analisis->level_dampak_inheren);
        $analisis->level_risiko_inheren = $hasil['besaran_risiko'];

        return $analisis;
    }

    /** Hitung & isi level_risiko_residual pada model TrnAnalisisRisiko (belum disimpan), jika kedua level residual sudah diisi */
    public function isiLevelResidual(TrnAnalisisRisiko $analisis): TrnAnalisisRisiko
    {
        if ($analisis->level_kemungkinan_residual && $analisis->level_dampak_residual) {
            $hasil = $this->hitungLevel($analisis->level_kemungkinan_residual, $analisis->level_dampak_residual);
            $analisis->level_risiko_residual = $hasil['besaran_risiko'];
        }

        return $analisis;
    }
}

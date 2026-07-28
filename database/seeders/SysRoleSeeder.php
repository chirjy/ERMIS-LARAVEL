<?php

namespace Database\Seeders;

use App\Models\SysRole;
use Illuminate\Database\Seeder;

class SysRoleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Lini 1 — UPR
            ['kode' => 'PENGELOLA_RISIKO', 'nama' => 'Pengelola Risiko', 'lini' => 1],
            ['kode' => 'KEPALA_UPR', 'nama' => 'Kepala UPR (Kepala UPT/Unit Kerja Pusat/Eselon I/Manajemen Puncak)', 'lini' => 1],
            // Lini 2 — SPI / Auditor Internal
            ['kode' => 'KETUA_TIM_SPI_UNIT_KERJA', 'nama' => 'Ketua Tim SPI Unit Kerja', 'lini' => 2],
            ['kode' => 'AUDITOR_INTERNAL', 'nama' => 'Auditor Internal', 'lini' => 2],
            ['kode' => 'KOORDINATOR_AUDITOR_INTERNAL', 'nama' => 'Koordinator Auditor Internal', 'lini' => 2],
            // Lini 3 — Inspektorat Utama
            ['kode' => 'INSPEKTUR_UTAMA', 'nama' => 'Inspektur Utama', 'lini' => 3],
            ['kode' => 'JF_AUDITOR', 'nama' => 'Jabatan Fungsional Auditor (APIP)', 'lini' => 3],
            // Lintas lini
            ['kode' => 'SEKRETARIAT_KMR', 'nama' => 'Sekretariat Komite Manajemen Risiko', 'lini' => null],
        ];

        foreach ($data as $row) {
            SysRole::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\RefAreaDampak;
use App\Models\RefKriteriaDampak;
use Illuminate\Database\Seeder;

/**
 * DATA RESMI — Kepka BPOM LAMPIRAN I BAB V.D.6 "Kriteria Level Dampak Downside
 * Risk". Beberapa area (Sanksi, Gangguan Layanan, Reputasi) memiliki kriteria
 * berjenjang berbeda per tingkatan UPR; beberapa lainnya (Beban Keuangan
 * Negara, K3, Penurunan Kinerja) berlaku sama untuk seluruh UPR.
 *
 * Catatan: untuk area "Sanksi pidana/perdata/administrasi", Kepka hanya
 * memberi kriteria eksplisit untuk level 4 (Signifikan) & 5 (Sangat
 * Signifikan) — level 1-3 sengaja tidak diseed karena aslinya kosong ("-")
 * di tabel resmi (murni judgment kualitatif untuk level di bawah itu).
 */
class RefKriteriaDampakSeeder extends Seeder
{
    public function run(): void
    {
        $areaId = fn (string $nama) => RefAreaDampak::where('nama', $nama)->where('jenis_risiko', 'DOWNSIDE')->value('id');

        $this->seedBebanKeuanganNegara($areaId('Beban Keuangan Negara'));
        $this->seedReputasi($areaId('Reputasi'));
        $this->seedSanksi($areaId('Sanksi Pidana/Perdata/Administratif'));
        $this->seedK3($areaId('Kecelakaan & Keselamatan Kerja'));
        $this->seedGangguanLayanan($areaId('Gangguan Layanan Organisasi'));
        $this->seedPenurunanKinerja($areaId('Penurunan Kinerja'));
    }

    private function simpan(int $areaId, string $tingkatan, int $level, string $label, string $deskripsi, array $parameter = []): void
    {
        RefKriteriaDampak::updateOrCreate(
            ['area_dampak_id' => $areaId, 'tingkatan_upr' => $tingkatan, 'level' => $level],
            ['label_level' => $label, 'deskripsi_kriteria' => $deskripsi, 'parameter_kuantitatif' => $parameter]
        );
    }

    private function seedBebanKeuanganNegara(int $areaId): void
    {
        $labels = ['Tidak Signifikan', 'Kurang Signifikan', 'Cukup Signifikan', 'Signifikan', 'Sangat Signifikan'];
        $data = [
            [1, '≤ 0,01% dari total anggaran non belanja pegawai pada UPR', ['max_persen' => 0.01]],
            [2, '> 0,01% s.d 0,1% dari total anggaran non belanja pegawai pada UPR', ['min_persen' => 0.01, 'max_persen' => 0.1]],
            [3, '> 0,1% s.d 1% dari total anggaran non belanja pegawai pada UPR', ['min_persen' => 0.1, 'max_persen' => 1]],
            [4, '> 1% s.d 5% dari total anggaran non belanja pegawai pada UPR', ['min_persen' => 1, 'max_persen' => 5]],
            [5, '> 5% dari total anggaran non belanja pegawai pada UPR', ['min_persen' => 5]],
        ];
        foreach ($data as [$level, $desc, $param]) {
            $this->simpan($areaId, 'SELURUH_UPR', $level, $labels[$level - 1], $desc, $param);
        }
    }

    private function seedReputasi(int $areaId): void
    {
        $labels = ['Tidak Signifikan', 'Kurang Signifikan', 'Cukup Signifikan', 'Signifikan', 'Sangat Signifikan'];

        // Manajemen Puncak & UPR Utama (kriteria sama, digandakan ke 2 tingkatan)
        $puncak = [
            [1, 'Tidak ada keluhan pelanggan yang signifikan ke organisasi.', []],
            [2, 'Jumlah keluhan pelanggan ke organisasi ≤ 10.', ['max_keluhan' => 10]],
            [3, 'Jumlah keluhan ke organisasi > 10.', ['min_keluhan' => 10]],
            [4, 'Pemberitaan negatif di media massa lokal.', []],
            [5, 'Pemberitaan negatif di media massa nasional atau internasional.', []],
        ];
        foreach (['MANAJEMEN_PUNCAK', 'UPR_UTAMA'] as $tingkatan) {
            foreach ($puncak as [$level, $desc, $param]) {
                $this->simpan($areaId, $tingkatan, $level, $labels[$level - 1], $desc, $param);
            }
        }

        // UPR Unit Kerja Pusat & Unit Pelaksana Teknis (kriteria sama, digandakan ke 2 tingkatan)
        $unitKerja = [
            [1, 'Jumlah keluhan ke organisasi ≤ 3; Tingkat IPKP 3,6 s.d 4 (skala 4).', ['max_keluhan' => 3, 'ipkp_min' => 3.6]],
            [2, 'Jumlah keluhan ke organisasi 3 s.d 5; Tingkat IPKP 3,2 s.d 3,59 (skala 4).', ['min_keluhan' => 3, 'max_keluhan' => 5, 'ipkp_min' => 3.2, 'ipkp_max' => 3.59]],
            [3, 'Jumlah keluhan ke organisasi > 5; Tingkat IPKP 2,8 s.d 3,19 (skala 4).', ['min_keluhan' => 5, 'ipkp_min' => 2.8, 'ipkp_max' => 3.19]],
            [4, 'Pemberitaan negatif di media massa lokal atau masif di media sosial; Tingkat IPKP 2,4 s.d 2,79 (skala 4).', ['ipkp_min' => 2.4, 'ipkp_max' => 2.79]],
            [5, 'Pemberitaan negatif di media massa nasional dan/atau internasional; Tingkat IPKP < 2,4 (skala 4).', ['ipkp_max' => 2.4]],
        ];
        foreach (['UNIT_KERJA_PUSAT', 'UNIT_PELAKSANA_TEKNIS'] as $tingkatan) {
            foreach ($unitKerja as [$level, $desc, $param]) {
                $this->simpan($areaId, $tingkatan, $level, $labels[$level - 1], $desc, $param);
            }
        }
    }

    private function seedSanksi(int $areaId): void
    {
        $labels = [4 => 'Signifikan', 5 => 'Sangat Signifikan'];

        $data = [
            'MANAJEMEN_PUNCAK' => [
                4 => 'Pidana ≤ 5 tahun atau tersangka/terdakwa Pejabat Eselon I/II/III/IV atau setara, JF, JFU; Perdata ≤ 10 M; Administratif: tergugat setingkat Eselon I/II/III/IV atau setara, JF, JFU.',
                5 => 'Pidana > 5 tahun atau tersangka/terdakwa Kepala BPOM; Perdata > 10 M; Administratif: tergugat Kepala BPOM.',
            ],
            'UPR_UTAMA' => [
                4 => 'Pidana ≤ 2 tahun atau tersangka/terdakwa Pejabat Eselon II/III/IV atau setara, JF, JFU; Perdata ≤ 5 M; Administratif: tergugat setingkat Eselon II/III/IV atau setara, JF, JFU.',
                5 => 'Pidana > 2 tahun atau tersangka/terdakwa Pejabat Eselon I; Perdata > 5 M; Administratif: tergugat Pejabat Eselon I.',
            ],
            'UNIT_KERJA_PUSAT' => [
                4 => 'Pidana ≤ 1 tahun atau tersangka/terdakwa Pejabat Eselon III/IV atau setara, JF, JFU; Perdata ≤ 1 M; Administratif: tergugat setingkat Eselon IV atau setara, JF, JFU.',
                5 => 'Pidana > 1 tahun atau tersangka/terdakwa Pejabat Eselon II; Perdata > 1 M; Administratif: tergugat Pejabat Eselon II.',
            ],
            'UNIT_PELAKSANA_TEKNIS' => [
                4 => 'Pidana ≤ 1 tahun; Perdata ≤ 100 juta; Administratif: tergugat selain Kepala Unit Pelaksana Teknis.',
                5 => 'Pidana > 1 tahun atau tersangka/terdakwa Pejabat Eselon III; Perdata > 100 juta; Administratif: tergugat Kepala Unit Pelaksana Teknis.',
            ],
        ];

        foreach ($data as $tingkatan => $levels) {
            foreach ($levels as $level => $desc) {
                $this->simpan($areaId, $tingkatan, $level, $labels[$level], $desc);
            }
        }
    }

    private function seedK3(int $areaId): void
    {
        $data = [
            [1, 'Tidak Signifikan', 'Tidak berbahaya, pegawai mampu bekerja pada hari yang sama.'],
            [2, 'Kurang Signifikan', 'Cedera/gangguan kesehatan fisik atau mental ringan; tidak mampu bertugas >1 hari s.d 1 minggu.'],
            [3, 'Cukup Signifikan', 'Cedera/gangguan kesehatan fisik atau mental sedang; tidak mampu bertugas >1 minggu s.d 4 minggu.'],
            [4, 'Signifikan', 'Cedera/gangguan kesehatan fisik atau mental berat; tidak mampu bertugas >4 minggu, atau cacat tetap/gangguan jiwa permanen.'],
            [5, 'Sangat Signifikan', 'Kematian.'],
        ];
        foreach ($data as [$level, $label, $desc]) {
            $this->simpan($areaId, 'SELURUH_UPR', $level, $label, $desc);
        }
    }

    private function seedGangguanLayanan(int $areaId): void
    {
        $labels = ['Tidak Signifikan', 'Kurang Signifikan', 'Cukup Signifikan', 'Signifikan', 'Sangat Signifikan'];

        // [tingkatan => [batas_atas_persen per level 1-4 (level 5 = sisanya)]]
        $batas = [
            'MANAJEMEN_PUNCAK' => [25, 50, 75, 90],
            'UPR_UTAMA' => [15, 40, 65, 80],
            'UNIT_KERJA_PUSAT' => [10, 25, 50, 65],
            'UNIT_PELAKSANA_TEKNIS' => [5, 15, 35, 50],
        ];

        foreach ($batas as $tingkatan => [$b1, $b2, $b3, $b4]) {
            $this->simpan($areaId, $tingkatan, 1, $labels[0], "x < {$b1}% dari jam operasional layanan harian", ['max_persen' => $b1]);
            $this->simpan($areaId, $tingkatan, 2, $labels[1], "{$b1}% ≤ x < {$b2}% dari jam operasional layanan harian", ['min_persen' => $b1, 'max_persen' => $b2]);
            $this->simpan($areaId, $tingkatan, 3, $labels[2], "{$b2}% ≤ x < {$b3}% dari jam operasional layanan harian", ['min_persen' => $b2, 'max_persen' => $b3]);
            $this->simpan($areaId, $tingkatan, 4, $labels[3], "{$b3}% ≤ x < {$b4}% dari jam operasional layanan harian", ['min_persen' => $b3, 'max_persen' => $b4]);
            $this->simpan($areaId, $tingkatan, 5, $labels[4], "x ≥ {$b4}% dari jam operasional layanan harian", ['min_persen' => $b4]);
        }
    }

    private function seedPenurunanKinerja(int $areaId): void
    {
        $data = [
            [1, 'Tidak Signifikan', 'x ≤ 5% dari target kinerja', ['max_persen' => 5]],
            [2, 'Kurang Signifikan', '5% < x ≤ 10% dari target kinerja', ['min_persen' => 5, 'max_persen' => 10]],
            [3, 'Cukup Signifikan', '10% < x ≤ 20% dari target kinerja', ['min_persen' => 10, 'max_persen' => 20]],
            [4, 'Signifikan', '20% < x ≤ 25% dari target kinerja', ['min_persen' => 20, 'max_persen' => 25]],
            [5, 'Sangat Signifikan', 'x > 25% dari target kinerja', ['min_persen' => 25]],
        ];
        foreach ($data as [$level, $label, $desc, $param]) {
            $this->simpan($areaId, 'SELURUH_UPR', $level, $label, $desc, $param);
        }
    }
}

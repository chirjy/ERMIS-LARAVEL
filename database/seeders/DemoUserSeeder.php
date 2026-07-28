<?php

namespace Database\Seeders;

use App\Models\SysRole;
use App\Models\SysUpt;
use App\Models\SysUser;
use App\Models\SysUserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Membuat akun demo untuk masing-masing lini supaya alur reviu 3 lini
 * bisa langsung diuji coba begitu aplikasi pertama kali dijalankan.
 * GANTI/HAPUS seeder ini sebelum go-live produksi.
 */
class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $upt = SysUpt::where('kode', 'LOKA-KOTBAR')->first();

        $akun = [
            ['email' => 'pengelola@lokapom-kotbar.test', 'nama' => 'Pengelola Risiko Demo', 'role' => 'PENGELOLA_RISIKO'],
            ['email' => 'kepala@lokapom-kotbar.test', 'nama' => 'Kepala UPR Demo', 'role' => 'KEPALA_UPR'],
            ['email' => 'spi@lokapom-kotbar.test', 'nama' => 'Ketua Tim SPI Demo', 'role' => 'KETUA_TIM_SPI_UNIT_KERJA'],
            ['email' => 'auditor@lokapom-kotbar.test', 'nama' => 'Auditor Internal Demo', 'role' => 'AUDITOR_INTERNAL'],
        ];

        foreach ($akun as $item) {
            $user = SysUser::updateOrCreate(
                ['email' => $item['email']],
                [
                    'id' => (string) Str::uuid(),
                    'upt_id' => $upt->id,
                    'nama' => $item['nama'],
                    'password' => Hash::make('password'),
                    'aktif' => true,
                ]
            );

            $role = SysRole::where('kode', $item['role'])->first();

            SysUserRole::updateOrCreate([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'upt_id' => $upt->id,
            ], [
                'id' => (string) Str::uuid(),
            ]);
        }

        $this->command?->info('Akun demo dibuat (password default: "password"): '.
            implode(', ', array_column($akun, 'email')));
    }
}

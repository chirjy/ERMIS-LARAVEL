<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_kriteria_dampaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_dampak_id')->constrained('ref_area_dampaks');
            // Sebagian area dampak (Sanksi, Gangguan Layanan, Reputasi) punya kriteria
            // berjenjang berbeda tergantung tingkatan UPR; sebagian lain (Beban Keuangan
            // Negara, K3, Penurunan Kinerja) berlaku sama untuk "Seluruh UPR".
            $table->enum('tingkatan_upr', [
                'SELURUH_UPR', 'MANAJEMEN_PUNCAK', 'UPR_UTAMA', 'UNIT_KERJA_PUSAT', 'UNIT_PELAKSANA_TEKNIS',
            ])->default('SELURUH_UPR');
            $table->unsignedTinyInteger('level'); // 1-5
            $table->string('label_level', 40); // Tidak Signifikan ... Sangat Signifikan
            $table->text('deskripsi_kriteria');
            $table->json('parameter_kuantitatif')->nullable(); // angka persis (%, rupiah, jam, tahun) utk kalkulasi/validasi
            $table->timestamps();

            $table->unique(['area_dampak_id', 'tingkatan_upr', 'level'], 'ref_kriteria_dampaks_unik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_kriteria_dampaks');
    }
};

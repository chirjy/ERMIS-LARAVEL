<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_kaidah_pernyataan_risikos', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kaidah', 10)->unique(); // K01-K08, dipetakan dari BAB IV.D.2 huruf a-h
            $table->string('judul', 150);
            $table->text('deskripsi_kaidah');
            $table->text('contoh_benar')->nullable();
            $table->text('contoh_salah')->nullable();
            $table->enum('tipe_pemeriksaan', [
                'OTOMATIS_KATA_TERLARANG',
                'OTOMATIS_NEGASI_SASARAN',
                'OTOMATIS_CAMPUR_SEBAB_DAMPAK',
                'OTOMATIS_MINIMAL_DOWNSIDE',
                'MANUAL_JUDGMENT',
            ]);
            $table->json('parameter_pemeriksaan')->nullable(); // mis. daftar kata terlarang
            $table->enum('tingkat_pelanggaran', ['ALERT_BLOKIR', 'ALERT_PERINGATAN'])->default('ALERT_PERINGATAN');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_kaidah_pernyataan_risikos');
    }
};

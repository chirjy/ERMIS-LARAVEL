<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_kriteria_kemungkinans', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level')->unique(); // 1-5
            $table->string('label', 30); // Hampir Tidak Terjadi ... Hampir Pasti Terjadi
            // Kriteria "bukan kategori frekuensi rendah" (non low frequency event)
            $table->decimal('probabilitas_min_persen', 5, 2)->nullable();
            $table->decimal('probabilitas_max_persen', 5, 2)->nullable();
            $table->string('kriteria_jumlah_frekuensi_non_low', 100)->nullable();
            // Kriteria "kejadian yang jarang terjadi" (low frequency event)
            $table->string('kriteria_frekuensi_low', 100)->nullable();
            $table->text('contoh_kejadian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_kriteria_kemungkinans');
    }
};

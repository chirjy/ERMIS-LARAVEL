<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trn_analisis_risikos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('identifikasi_risiko_id')->constrained('trn_identifikasi_risikos')->onDelete('cascade');
            $table->foreignId('area_dampak_id')->constrained('ref_area_dampaks'); // kol.14

            // Risiko Inheren (kol.15-17)
            $table->unsignedTinyInteger('level_kemungkinan_inheren');
            $table->unsignedTinyInteger('level_dampak_inheren');
            $table->unsignedTinyInteger('level_risiko_inheren'); // hasil lookup ref_matriks_risikos, dihitung service

            // Aktivitas Pengendalian saat ini (kol.18-21)
            $table->text('aktivitas_pengendalian')->nullable();
            $table->text('atribut_pengendalian')->nullable();
            $table->text('penilaian_kelemahan_pengendalian')->nullable();
            $table->enum('simpulan_efektivitas_pengendalian', ['EFEKTIF', 'TIDAK_EFEKTIF'])->nullable();

            // Risiko Residual (kol.22-24)
            $table->unsignedTinyInteger('level_kemungkinan_residual')->nullable();
            $table->unsignedTinyInteger('level_dampak_residual')->nullable();
            $table->unsignedTinyInteger('level_risiko_residual')->nullable();

            $table->boolean('is_top_risk')->default(false);
            $table->unsignedInteger('urutan_prioritas')->nullable();

            $table->foreignUuid('created_by')->constrained('sys_users');
            $table->timestamps();

            $table->index(['identifikasi_risiko_id', 'level_risiko_residual']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trn_analisis_risikos');
    }
};

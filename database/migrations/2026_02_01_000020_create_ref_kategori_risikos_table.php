<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_kategori_risikos', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 2)->unique(); // KB, RP, FR, HK, KM, SP, KK, OP
            $table->string('nama', 60);
            $table->unsignedTinyInteger('prioritas'); // 1-8, tie-break evaluasi risiko (BAB VI.D.1.a.3)
            $table->text('penjelasan')->nullable();
            // Kolom knowledge base (Revisi 3 / BAB D.3) — ditambahkan langsung di sini
            // supaya tidak perlu migration tambahan terpisah pada fase fondasi ini.
            $table->text('contoh_kasus')->nullable();
            $table->text('area_dampak_lazim')->nullable();
            $table->text('kata_kunci_identifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_kategori_risikos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_metode_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique(); // FGD, KUESIONER, EXPERT_JUDGEMENT, KONSENSUS, ANALISIS_DATA, SIMULASI_PROYEKSI
            $table->string('nama', 60);
            $table->text('deskripsi');
            $table->enum('cocok_untuk', ['KEMUNGKINAN', 'DAMPAK', 'KEDUANYA']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_metode_penilaians');
    }
};

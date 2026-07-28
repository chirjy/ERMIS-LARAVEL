<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_level_risikos', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('rentang_min');
            $table->unsignedTinyInteger('rentang_max');
            $table->string('label', 20);        // Sangat Rendah, Rendah, Sedang, Tinggi, Sangat Tinggi
            $table->string('simbol_warna', 10); // Biru, Hijau, Kuning, Jingga, Merah
            $table->string('warna_hex', 7)->default('#64748b');
            $table->text('tindakan')->nullable();
            $table->boolean('wajib_pengujian_pengendalian')->default(false); // true utk Tinggi & Sangat Tinggi (BAB VII.B)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_level_risikos');
    }
};

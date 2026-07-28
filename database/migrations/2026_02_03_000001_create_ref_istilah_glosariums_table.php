<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_istilah_glosariums', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('nomor_urut')->unique(); // 1-40, BAB I.C
            $table->string('istilah', 120);
            $table->text('definisi');
            $table->string('referensi_bab', 20)->nullable();
            $table->json('konteks_pemakaian')->nullable(); // daftar modul/field terkait, utk tooltip kontekstual
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_istilah_glosariums');
    }
};

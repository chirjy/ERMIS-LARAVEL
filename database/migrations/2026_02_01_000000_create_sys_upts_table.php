<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_upts', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();   // mis. "LOKA-KOTBAR"
            $table->string('nama', 150);
            $table->enum('jenjang', [
                'UNIT_PELAKSANA_TEKNIS',
                'UNIT_KERJA_PUSAT',
                'ESELON_I',
                'MANAJEMEN_PUNCAK',
            ])->default('UNIT_PELAKSANA_TEKNIS');
            $table->string('provinsi', 100)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_upts');
    }
};

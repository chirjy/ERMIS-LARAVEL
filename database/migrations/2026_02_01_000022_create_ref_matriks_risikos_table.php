<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_matriks_risikos', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level_kemungkinan'); // 1-5
            $table->unsignedTinyInteger('level_dampak');       // 1-5
            $table->unsignedTinyInteger('besaran_risiko');     // nilai matriks (BUKAN kemungkinan x dampak)
            $table->timestamps();
            $table->unique(['level_kemungkinan', 'level_dampak']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_matriks_risikos');
    }
};

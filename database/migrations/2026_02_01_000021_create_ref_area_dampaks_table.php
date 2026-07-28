<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_area_dampaks', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 60); // Beban Keuangan Negara, Reputasi, Sanksi Pidana/Perdata/Administratif,
                                         // Kecelakaan & Keselamatan Kerja, Gangguan Layanan Organisasi, Penurunan Kinerja, dst
            $table->enum('jenis_risiko', ['DOWNSIDE', 'UPSIDE']);
            $table->unsignedTinyInteger('prioritas'); // 1-6 utk downside (BAB VI.D.1.a.2)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_area_dampaks');
    }
};

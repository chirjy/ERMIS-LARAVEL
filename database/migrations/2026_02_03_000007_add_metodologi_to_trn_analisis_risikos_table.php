<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trn_analisis_risikos', function (Blueprint $table) {
            // Metodologi Penilaian (BAB V.D.4 & D.5): dicatat agar transparan metode
            // apa yang dipakai UPR menentukan level kemungkinan/dampak.
            $table->foreignId('metode_penentuan_kemungkinan_id')->nullable()
                ->after('level_risiko_inheren')->constrained('ref_metode_penilaians');
            $table->foreignId('metode_penentuan_dampak_id')->nullable()
                ->after('metode_penentuan_kemungkinan_id')->constrained('ref_metode_penilaians');
            $table->text('uraian_dasar_pertimbangan')->nullable()
                ->after('metode_penentuan_dampak_id');
            $table->enum('pendekatan_kemungkinan', ['JUMLAH_FREKUENSI', 'PROBABILITAS'])->nullable()
                ->after('uraian_dasar_pertimbangan');
        });
    }

    public function down(): void
    {
        Schema::table('trn_analisis_risikos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('metode_penentuan_kemungkinan_id');
            $table->dropConstrainedForeignId('metode_penentuan_dampak_id');
            $table->dropColumn(['uraian_dasar_pertimbangan', 'pendekatan_kemungkinan']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trn_pemantauan_revius', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rtp_id')->constrained('trn_rencana_tindak_pengendalians');
            $table->text('uraian_target');
            $table->date('due_date');
            $table->string('pic', 150);
            $table->decimal('progress_persen', 5, 2)->default(0);
            $table->date('tanggal_progress');
            $table->enum('penilaian_kelemahan_pengendalian', ['TIDAK_SIGNIFIKAN', 'SIGNIFIKAN', 'MATERIAL'])->nullable();
            $table->enum('simpulan_efektivitas_pengendalian', ['EFEKTIF', 'TIDAK_EFEKTIF'])->nullable();
            $table->foreignUuid('dilaporkan_oleh')->constrained('sys_users');
            $table->timestamps();

            $table->index(['rtp_id', 'tanggal_progress']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trn_pemantauan_revius');
    }
};

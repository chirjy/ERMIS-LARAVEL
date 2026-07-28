<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trn_rencana_tindak_pengendalians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('analisis_risiko_id')->constrained('trn_analisis_risikos');
            $table->foreignId('upt_id')->constrained('sys_upts');

            $table->enum('opsi_respon_risiko', ['AVOID', 'REDUCE', 'SHARE', 'ACCEPT']);
            $table->text('uraian_mitigasi');
            $table->text('output_target');
            $table->string('pic', 150);
            $table->text('sumber_daya_dibutuhkan');
            $table->date('target_waktu_penyelesaian');

            // Target level risiko setelah mitigasi (BAB VII.D.1.d-e)
            $table->unsignedTinyInteger('kemungkinan_mitigasi')->nullable();
            $table->unsignedTinyInteger('dampak_mitigasi')->nullable();
            $table->unsignedTinyInteger('level_risiko_mitigasi')->nullable();

            // --- State machine tiga lini (identik pola Konteks/Identifikasi) ---
            $table->string('status', 30)->default('DRAFT');
            $table->text('catatan_penolakan')->nullable();
            $table->foreignUuid('direviu_oleh')->nullable()->constrained('sys_users'); // Lini 2
            $table->timestamp('direviu_at')->nullable();
            $table->foreignUuid('approved_by')->nullable()->constrained('sys_users');  // Lini 1
            $table->timestamp('approved_at')->nullable();
            $table->string('signature_hash', 64)->nullable();

            $table->foreignUuid('created_by')->constrained('sys_users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['upt_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trn_rencana_tindak_pengendalians');
    }
};

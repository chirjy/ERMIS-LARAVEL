<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trn_konteks_organisasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('upt_id')->constrained('sys_upts')->onDelete('cascade');
            $table->year('tahun_anggaran');
            $table->text('ruang_lingkup');
            $table->text('sasaran_organisasi');
            $table->json('stakeholder')->nullable();
            $table->json('peraturan_terkait')->nullable();
            $table->text('kriteria_kemungkinan_custom')->nullable(); // override BAB III.D.2.f.1
            $table->text('kriteria_dampak_custom')->nullable();

            // --- State machine tiga lini (Bagian B, Revisi 2) ---
            $table->string('status', 30)->default('DRAFT');
            $table->text('catatan_penolakan')->nullable();
            $table->foreignUuid('direviu_oleh')->nullable()->constrained('sys_users'); // Lini 2
            $table->timestamp('direviu_at')->nullable();
            $table->string('signature_hash_lini2', 64)->nullable();
            $table->foreignUuid('approved_by')->nullable()->constrained('sys_users');  // Lini 1 - Kepala UPR
            $table->timestamp('approved_at')->nullable();
            $table->string('signature_hash_lini1', 64)->nullable();

            $table->foreignUuid('created_by')->constrained('sys_users');
            $table->foreignUuid('updated_by')->nullable()->constrained('sys_users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['upt_id', 'tahun_anggaran', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trn_konteks_organisasis');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trn_identifikasi_risikos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('upt_id')->constrained('sys_upts')->onDelete('cascade');
            $table->year('tahun_anggaran');
            $table->foreignUuid('konteks_id')->constrained('trn_konteks_organisasis');

            $table->text('sasaran_strategis');             // kol.2
            $table->text('indikator_kinerja');              // kol.3
            $table->enum('isu', ['INTERNAL', 'EKSTERNAL']); // kol.4
            $table->text('kegiatan_proses_bisnis');         // kol.5
            $table->string('kode_risiko', 30);              // kol.6 — unik per UPT+tahun, BUKAN global
            $table->foreignId('kategori_risiko_id')->constrained('ref_kategori_risikos'); // kol.7
            $table->enum('jenis_risiko', ['DOWNSIDE', 'UPSIDE'])->default('DOWNSIDE');
            $table->text('pernyataan_risiko');              // kol.8
            $table->text('penyebab_risiko');                // kol.9
            $table->enum('sumber_risiko', ['INTERNAL', 'EKSTERNAL']); // kol.10
            $table->text('dampak_risiko');                  // kol.11
            $table->foreignUuid('pemilik_risiko_id')->constrained('sys_users'); // kol.12 — beda dgn created_by
            $table->json('pihak_terkait')->nullable();      // kol.13

            // --- State machine tiga lini ---
            $table->string('status', 30)->default('DRAFT');
            $table->text('catatan_penolakan')->nullable();
            $table->foreignUuid('direviu_oleh')->nullable()->constrained('sys_users');
            $table->timestamp('direviu_at')->nullable();
            $table->string('signature_hash_lini2', 64)->nullable();
            $table->foreignUuid('approved_by')->nullable()->constrained('sys_users');
            $table->timestamp('approved_at')->nullable();
            $table->string('signature_hash_lini1', 64)->nullable();

            $table->foreignUuid('created_by')->constrained('sys_users');
            $table->foreignUuid('updated_by')->nullable()->constrained('sys_users');
            $table->timestamps();
            $table->softDeletes();

            // PENTING: kode_risiko unik PER UPT PER TAHUN, bukan global.
            $table->unique(['upt_id', 'tahun_anggaran', 'kode_risiko']);
            $table->index(['upt_id', 'tahun_anggaran', 'status', 'kategori_risiko_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trn_identifikasi_risikos');
    }
};

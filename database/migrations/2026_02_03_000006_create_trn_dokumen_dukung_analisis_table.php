<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trn_dokumen_dukung_analisis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('analisis_risiko_id')->constrained('trn_analisis_risikos')->onDelete('cascade');
            $table->enum('jenis_dukungan', ['PAST_RECORD', 'RELEVANT_EXPERIENCE', 'RELEVANT_PUBLISHED_LITERATURE']);
            $table->enum('digunakan_untuk', ['KEMUNGKINAN', 'DAMPAK', 'KEDUANYA']);
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('ukuran_bytes');
            $table->text('keterangan')->nullable();
            $table->foreignUuid('diunggah_oleh')->constrained('sys_users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trn_dokumen_dukung_analisis');
    }
};

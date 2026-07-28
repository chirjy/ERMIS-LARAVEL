<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AnalisisRisikoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenDukungController;
use App\Http\Controllers\IdentifikasiRisikoController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\KonteksOrganisasiController;
use App\Http\Controllers\PemantauanController;
use App\Http\Controllers\RtpController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Konteks Organisasi
    Route::prefix('konteks-organisasi')->name('konteks.')->group(function () {
        Route::get('/', [KonteksOrganisasiController::class, 'index'])->name('index');
        Route::get('/create', [KonteksOrganisasiController::class, 'create'])->name('create');
        Route::post('/', [KonteksOrganisasiController::class, 'store'])->name('store');
        Route::get('/{konteks}', [KonteksOrganisasiController::class, 'show'])->name('show');
        Route::get('/{konteks}/edit', [KonteksOrganisasiController::class, 'edit'])->name('edit');
        Route::put('/{konteks}', [KonteksOrganisasiController::class, 'update'])->name('update');
        Route::delete('/{konteks}', [KonteksOrganisasiController::class, 'destroy'])->name('destroy');

        Route::post('/{konteks}/ajukan-reviu', [KonteksOrganisasiController::class, 'ajukanReviu'])->name('ajukan-reviu');
        Route::post('/{konteks}/reviu-lini2', [KonteksOrganisasiController::class, 'reviuLini2'])->name('reviu-lini2');
        Route::post('/{konteks}/approve', [KonteksOrganisasiController::class, 'approve'])->name('approve');
        Route::post('/{konteks}/reject', [KonteksOrganisasiController::class, 'reject'])->name('reject');
    });

    // Identifikasi Risiko
    Route::prefix('identifikasi-risiko')->name('identifikasi.')->group(function () {
        Route::get('/', [IdentifikasiRisikoController::class, 'index'])->name('index');
        Route::get('/create', [IdentifikasiRisikoController::class, 'create'])->name('create');
        Route::post('/', [IdentifikasiRisikoController::class, 'store'])->name('store');
        Route::get('/{risiko}', [IdentifikasiRisikoController::class, 'show'])->name('show');
        Route::get('/{risiko}/edit', [IdentifikasiRisikoController::class, 'edit'])->name('edit');
        Route::put('/{risiko}', [IdentifikasiRisikoController::class, 'update'])->name('update');
        Route::delete('/{risiko}', [IdentifikasiRisikoController::class, 'destroy'])->name('destroy');

        Route::post('/{risiko}/ajukan-reviu', [IdentifikasiRisikoController::class, 'ajukanReviu'])->name('ajukan-reviu');
        Route::post('/{risiko}/reviu-lini2', [IdentifikasiRisikoController::class, 'reviuLini2'])->name('reviu-lini2');
        Route::post('/{risiko}/approve', [IdentifikasiRisikoController::class, 'approve'])->name('approve');
        Route::post('/{risiko}/reject', [IdentifikasiRisikoController::class, 'reject'])->name('reject');

        // Analisis Risiko (nested)
        Route::get('/{risiko}/analisis/create', [AnalisisRisikoController::class, 'create'])->name('analisis.create');
        Route::post('/{risiko}/analisis', [AnalisisRisikoController::class, 'store'])->name('analisis.store');
        Route::get('/{risiko}/analisis/{analisis}/edit', [AnalisisRisikoController::class, 'edit'])->name('analisis.edit');
        Route::put('/{risiko}/analisis/{analisis}', [AnalisisRisikoController::class, 'update'])->name('analisis.update');

        Route::post('/preview-validasi-pernyataan', [IdentifikasiRisikoController::class, 'previewValidasiPernyataan'])->name('preview-validasi-pernyataan');
    });

    Route::post('/analisis-risiko/preview', [AnalisisRisikoController::class, 'preview'])->name('analisis.preview');

    // Dokumen Dukung Analisis (past record / pengalaman / literatur - Revisi 3 Knowledge Base)
    Route::post('/analisis-risiko/{analisis}/dokumen-dukung', [DokumenDukungController::class, 'store'])->name('dokumen-dukung.store');
    Route::get('/dokumen-dukung/{dokumen}/download', [DokumenDukungController::class, 'download'])->name('dokumen-dukung.download');
    Route::delete('/dokumen-dukung/{dokumen}', [DokumenDukungController::class, 'destroy'])->name('dokumen-dukung.destroy');

    // Knowledge Base (Revisi 3): glosarium, kriteria kemungkinan/dampak, kaidah pernyataan risiko
    Route::prefix('knowledge-base')->name('knowledge-base.')->group(function () {
        Route::get('/glosarium', [KnowledgeBaseController::class, 'glosarium'])->name('glosarium');
        Route::get('/kriteria', [KnowledgeBaseController::class, 'kriteria'])->name('kriteria');
        Route::get('/kaidah', [KnowledgeBaseController::class, 'kaidah'])->name('kaidah');
    });

    // RTP (Rencana Tindak Pengendalian) - dibuat dari sebuah Analisis Risiko
    Route::get('/analisis-risiko/{analisis}/rtp/create', [RtpController::class, 'create'])->name('rtp.create');
    Route::post('/analisis-risiko/{analisis}/rtp', [RtpController::class, 'store'])->name('rtp.store');

    Route::prefix('rtp')->name('rtp.')->group(function () {
        Route::get('/{rtp}', [RtpController::class, 'show'])->name('show');
        Route::get('/{rtp}/edit', [RtpController::class, 'edit'])->name('edit');
        Route::put('/{rtp}', [RtpController::class, 'update'])->name('update');
        Route::delete('/{rtp}', [RtpController::class, 'destroy'])->name('destroy');

        Route::post('/{rtp}/ajukan-reviu', [RtpController::class, 'ajukanReviu'])->name('ajukan-reviu');
        Route::post('/{rtp}/reviu-lini2', [RtpController::class, 'reviuLini2'])->name('reviu-lini2');
        Route::post('/{rtp}/approve', [RtpController::class, 'approve'])->name('approve');
        Route::post('/{rtp}/reject', [RtpController::class, 'reject'])->name('reject');

        // Pemantauan / Reviu (Anak Lampiran I.c)
        Route::get('/{rtp}/pemantauan/create', [PemantauanController::class, 'create'])->name('pemantauan.create');
        Route::post('/{rtp}/pemantauan', [PemantauanController::class, 'store'])->name('pemantauan.store');
    });
});

@extends('layouts.app')

@section('title', 'Kaidah Pernyataan Risiko - ERMIS BPOM')
@section('page-title', 'Kaidah Pernyataan Risiko')

@section('content')

@if (count($tanpaDownside) > 0)
    <div class="card p-5 mb-6 border-amber-200 bg-amber-50">
        <h3 class="font-semibold text-sm text-amber-800 mb-2">
            ⚠ K02 — Sasaran/Indikator Kinerja Tahun {{ $tahunIni }} Tanpa Downside Risk
        </h3>
        <p class="text-xs text-amber-700 mb-3">
            "Terhadap setiap Sasaran Strategis dan Indikator Kinerja diidentifikasi minimal 1 (satu)
            risiko downside risk" (BAB IV.D.2.b). Kombinasi berikut di UPT Anda belum punya downside risk:
        </p>
        <ul class="text-sm text-amber-800 space-y-1 list-disc list-inside">
            @foreach ($tanpaDownside as $item)
                <li>{{ $item['sasaran_strategis'] }} — {{ $item['indikator_kinerja'] }} ({{ $item['jumlah_risiko'] }} risiko upside saja)</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    @foreach ($kaidahs as $kaidah)
        <div class="card p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-slate-900">{{ $kaidah->kode_kaidah }} — {{ $kaidah->judul }}</h3>
                <span class="badge {{ $kaidah->tipe_pemeriksaan === 'MANUAL_JUDGMENT' ? 'bg-slate-100 text-slate-600' : 'bg-teal-100 text-teal-800' }}">
                    {{ $kaidah->tipe_pemeriksaan === 'MANUAL_JUDGMENT' ? 'Judgment Manual' : 'Divalidasi Otomatis' }}
                </span>
            </div>
            <p class="text-sm text-slate-600 mb-3">{{ $kaidah->deskripsi_kaidah }}</p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                @if ($kaidah->contoh_benar)
                    <div class="rounded-md bg-teal-50 border border-teal-200 px-3 py-2">
                        <div class="text-xs font-semibold text-teal-700 mb-1">✓ Contoh Benar</div>
                        <div class="text-teal-800">{{ $kaidah->contoh_benar }}</div>
                    </div>
                @endif
                @if ($kaidah->contoh_salah)
                    <div class="rounded-md bg-red-50 border border-red-200 px-3 py-2">
                        <div class="text-xs font-semibold text-red-700 mb-1">✗ Contoh Salah</div>
                        <div class="text-red-800">{{ $kaidah->contoh_salah }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection

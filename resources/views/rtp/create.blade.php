@extends('layouts.app')

@section('title', 'RTP Baru - ERMIS BPOM')
@section('page-title', 'Rencana Tindak Pengendalian Baru')

@section('content')
<div class="card p-4 mb-4 max-w-2xl bg-slate-50">
    <div class="text-xs text-slate-500 mb-1">Untuk Analisis Risiko:</div>
    <div class="text-sm font-medium">{{ $analisis->identifikasiRisiko->kode_risiko }} — {{ $analisis->areaDampak->nama }}</div>
    <div class="text-xs text-slate-500 mt-1">
        Level Risiko Residual/Inheren:
        <span class="font-semibold">{{ $analisis->level_risiko_residual ?? $analisis->level_risiko_inheren }}</span>
        (wajib RTP karena &gt; 15)
    </div>
</div>

<div class="card p-6 max-w-2xl">
    <form method="POST" action="{{ route('rtp.store', $analisis) }}" class="space-y-4">
        @csrf
        @include('rtp._form')
        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan RTP sebagai Draft</button>
            <a href="{{ route('identifikasi.show', $analisis->identifikasiRisiko) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

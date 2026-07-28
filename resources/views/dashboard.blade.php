@extends('layouts.app')

@section('title', 'Dashboard - ERMIS BPOM')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4">
        <div class="text-xs text-slate-500 mb-1">Konteks Draft</div>
        <div class="text-2xl font-bold text-slate-900">{{ $ringkasan['konteks_draft'] }}</div>
    </div>
    <div class="card p-4">
        <div class="text-xs text-slate-500 mb-1">Risiko Draft</div>
        <div class="text-2xl font-bold text-slate-900">{{ $ringkasan['risiko_draft'] }}</div>
    </div>
    <div class="card p-4">
        <div class="text-xs text-slate-500 mb-1">Menunggu Reviu Lini 2</div>
        <div class="text-2xl font-bold text-amber-600">{{ $ringkasan['risiko_menunggu_reviu'] }}</div>
    </div>
    <div class="card p-4">
        <div class="text-xs text-slate-500 mb-1">Disetujui Kepala UPT</div>
        <div class="text-2xl font-bold text-teal-700">{{ $ringkasan['risiko_disetujui'] }}</div>
    </div>
</div>

<div class="card">
    <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between">
        <h3 class="font-semibold text-slate-900 text-sm">Identifikasi Risiko Terbaru</h3>
        <a href="{{ route('identifikasi.index') }}" class="text-xs text-ermis-teal hover:underline">Lihat semua &rarr;</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-2">Kode Risiko</th>
                <th class="text-left px-5 py-2">Pernyataan Risiko</th>
                <th class="text-left px-5 py-2">Kategori</th>
                <th class="text-left px-5 py-2">Level Inheren</th>
                <th class="text-left px-5 py-2">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($risikoTerbaru as $risiko)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3">
                        <a href="{{ route('identifikasi.show', $risiko) }}" class="text-ermis-tealdark font-medium hover:underline">
                            {{ $risiko->kode_risiko }}
                        </a>
                    </td>
                    <td class="px-5 py-3 max-w-sm truncate">{{ $risiko->pernyataan_risiko }}</td>
                    <td class="px-5 py-3">{{ $risiko->kategoriRisiko->kode }}</td>
                    <td class="px-5 py-3">
                        {{ $risiko->analisisTerakhir?->level_risiko_inheren ?? '—' }}
                    </td>
                    <td class="px-5 py-3">@include('partials.status-badge', ['status' => $risiko->status])</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-6 text-center text-slate-400">Belum ada data identifikasi risiko.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

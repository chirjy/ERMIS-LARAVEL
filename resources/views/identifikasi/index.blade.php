@extends('layouts.app')

@section('title', 'Identifikasi Risiko - ERMIS BPOM')
@section('page-title', 'Identifikasi Risiko')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('identifikasi.create') }}" class="btn-primary">+ Identifikasi Risiko Baru</a>
</div>

<div class="card overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-2">Kode Risiko</th>
                <th class="text-left px-5 py-2">Pernyataan Risiko</th>
                <th class="text-left px-5 py-2">Kategori</th>
                <th class="text-left px-5 py-2">Level Inheren</th>
                <th class="text-left px-5 py-2">Status</th>
                <th class="px-5 py-2"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($daftar as $risiko)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 font-medium">{{ $risiko->kode_risiko }}</td>
                    <td class="px-5 py-3 max-w-md truncate">{{ $risiko->pernyataan_risiko }}</td>
                    <td class="px-5 py-3">{{ $risiko->kategoriRisiko->kode }}</td>
                    <td class="px-5 py-3">{{ $risiko->analisisTerakhir?->level_risiko_inheren ?? '—' }}</td>
                    <td class="px-5 py-3">@include('partials.status-badge', ['status' => $risiko->status])</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('identifikasi.show', $risiko) }}" class="text-ermis-tealdark hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-6 text-center text-slate-400">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $daftar->links() }}</div>
@endsection

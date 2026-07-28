@extends('layouts.app')

@section('title', 'Kriteria Kemungkinan & Dampak - ERMIS BPOM')
@section('page-title', 'Kriteria Kemungkinan &amp; Dampak Berjenjang')

@section('content')

<div class="card mb-6 overflow-x-auto">
    <div class="px-5 py-3 border-b border-slate-200">
        <h3 class="font-semibold text-sm text-slate-900">Kriteria Level Kemungkinan (BAB V.D.5)</h3>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
            <tr>
                <th class="text-left px-4 py-2">Level</th>
                <th class="text-left px-4 py-2">Label</th>
                <th class="text-left px-4 py-2">Probabilitas (non low-frequency)</th>
                <th class="text-left px-4 py-2">Jumlah Frekuensi (non low-frequency)</th>
                <th class="text-left px-4 py-2">Kriteria Low-Frequency Event</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach ($kemungkinans as $k)
                <tr>
                    <td class="px-4 py-2 font-semibold">{{ $k->level }}</td>
                    <td class="px-4 py-2">{{ $k->label }}</td>
                    <td class="px-4 py-2">
                        @if ($k->probabilitas_min_persen !== null)
                            {{ rtrim(rtrim($k->probabilitas_min_persen, '0'), '.') }}% &lt; X
                            @if ($k->probabilitas_max_persen) ≤ {{ rtrim(rtrim($k->probabilitas_max_persen, '0'), '.') }}% @endif
                        @else — @endif
                    </td>
                    <td class="px-4 py-2">{{ $k->kriteria_jumlah_frekuensi_non_low }}</td>
                    <td class="px-4 py-2">{{ $k->kriteria_frekuensi_low }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card mb-6">
    <div class="px-5 py-3 border-b border-slate-200">
        <h3 class="font-semibold text-sm text-slate-900">Metode Penilaian (BAB V.D.4)</h3>
    </div>
    <div class="divide-y divide-slate-100">
        @foreach ($metodePenilaians as $m)
            <div class="px-5 py-3 text-sm">
                <span class="font-semibold">{{ $m->nama }}</span>
                <span class="badge bg-slate-100 text-slate-600 ml-2">{{ $m->cocok_untuk }}</span>
                <p class="text-slate-600 mt-1">{{ $m->deskripsi }}</p>
            </div>
        @endforeach
    </div>
</div>

<div class="space-y-4">
    <h3 class="font-semibold text-sm text-slate-900">Kriteria Level Dampak per Area (BAB V.D.6)</h3>
    @foreach ($areaDampaks as $area)
        @php $kriteriaArea = $kriteriaDampak->get($area->id); @endphp
        @if ($kriteriaArea)
            <div class="card overflow-x-auto">
                <div class="px-5 py-3 border-b border-slate-200">
                    <h4 class="font-semibold text-sm text-slate-900">{{ $area->nama }}</h4>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                        <tr>
                            <th class="text-left px-4 py-2">Tingkatan UPR</th>
                            <th class="text-left px-4 py-2">Level</th>
                            <th class="text-left px-4 py-2">Label</th>
                            <th class="text-left px-4 py-2">Deskripsi Kriteria</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($kriteriaArea->sortBy(['tingkatan_upr', 'level']) as $kd)
                            <tr>
                                <td class="px-4 py-2">{{ str_replace('_', ' ', $kd->tingkatan_upr) }}</td>
                                <td class="px-4 py-2 font-semibold">{{ $kd->level }}</td>
                                <td class="px-4 py-2">{{ $kd->label_level }}</td>
                                <td class="px-4 py-2 text-slate-600">{{ $kd->deskripsi_kriteria }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach
</div>
@endsection

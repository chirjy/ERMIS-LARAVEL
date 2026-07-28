@extends('layouts.app')

@section('title', 'Konteks Organisasi - ERMIS BPOM')
@section('page-title', 'Konteks Organisasi')

@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('konteks.create') }}" class="btn-primary">+ Konteks Organisasi Baru</a>
</div>

<div class="card overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
            <tr>
                <th class="text-left px-5 py-2">Tahun</th>
                <th class="text-left px-5 py-2">Ruang Lingkup</th>
                <th class="text-left px-5 py-2">Dibuat Oleh</th>
                <th class="text-left px-5 py-2">Status</th>
                <th class="px-5 py-2"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($daftar as $konteks)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3">{{ $konteks->tahun_anggaran }}</td>
                    <td class="px-5 py-3 max-w-md truncate">{{ $konteks->ruang_lingkup }}</td>
                    <td class="px-5 py-3">{{ $konteks->createdBy->nama ?? '—' }}</td>
                    <td class="px-5 py-3">@include('partials.status-badge', ['status' => $konteks->status])</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('konteks.show', $konteks) }}" class="text-ermis-tealdark hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-6 text-center text-slate-400">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $daftar->links() }}</div>
@endsection

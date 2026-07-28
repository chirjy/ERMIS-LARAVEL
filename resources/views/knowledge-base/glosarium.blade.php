@extends('layouts.app')

@section('title', 'Glosarium - ERMIS BPOM')
@section('page-title', 'Glosarium Manajemen Risiko')

@section('content')
<form method="GET" class="mb-4 max-w-md">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari istilah atau definisi..." class="form-input">
</form>

<div class="card divide-y divide-slate-100">
    @forelse ($istilahs as $istilah)
        <div class="px-5 py-3">
            <div class="flex items-baseline gap-2">
                <span class="text-xs text-slate-400 w-8">{{ $istilah->nomor_urut }}.</span>
                <span class="font-semibold text-slate-900">{{ $istilah->istilah }}</span>
                @if ($istilah->referensi_bab)
                    <span class="text-xs text-slate-400 ml-auto">{{ $istilah->referensi_bab }}</span>
                @endif
            </div>
            <p class="text-sm text-slate-600 mt-1 pl-10">{{ $istilah->definisi }}</p>
        </div>
    @empty
        <p class="px-5 py-6 text-center text-slate-400">Tidak ditemukan istilah yang cocok.</p>
    @endforelse
</div>
@endsection

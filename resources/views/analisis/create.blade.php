@extends('layouts.app')

@section('title', 'Analisis Risiko Baru - ERMIS BPOM')
@section('page-title', 'Analisis Risiko: ' . $risiko->kode_risiko)

@section('content')
<div class="card p-6 max-w-2xl">
    <form method="POST" action="{{ route('identifikasi.analisis.store', $risiko) }}" class="space-y-4">
        @csrf
        @include('analisis._form')
        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan Analisis Risiko</button>
            <a href="{{ route('identifikasi.show', $risiko) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Identifikasi Risiko - ERMIS BPOM')
@section('page-title', 'Edit Identifikasi Risiko')

@section('content')
<div class="card p-6 max-w-3xl">
    <form method="POST" action="{{ route('identifikasi.update', $risiko) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('identifikasi._form')
        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('identifikasi.show', $risiko) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

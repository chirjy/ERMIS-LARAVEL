@extends('layouts.app')

@section('title', 'Identifikasi Risiko Baru - ERMIS BPOM')
@section('page-title', 'Identifikasi Risiko Baru')

@section('content')
<div class="card p-6 max-w-3xl">
    <form method="POST" action="{{ route('identifikasi.store') }}" class="space-y-4">
        @csrf
        @include('identifikasi._form')
        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan sebagai Draft</button>
            <a href="{{ route('identifikasi.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

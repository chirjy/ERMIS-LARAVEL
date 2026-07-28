@extends('layouts.app')

@section('title', 'Konteks Organisasi Baru - ERMIS BPOM')
@section('page-title', 'Konteks Organisasi Baru')

@section('content')
<div class="card p-6 max-w-3xl">
    <form method="POST" action="{{ route('konteks.store') }}" class="space-y-4">
        @csrf
        @include('konteks._form')
        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan sebagai Draft</button>
            <a href="{{ route('konteks.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

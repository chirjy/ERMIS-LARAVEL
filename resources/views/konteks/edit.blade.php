@extends('layouts.app')

@section('title', 'Edit Konteks Organisasi - ERMIS BPOM')
@section('page-title', 'Edit Konteks Organisasi')

@section('content')
<div class="card p-6 max-w-3xl">
    <form method="POST" action="{{ route('konteks.update', $konteks) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('konteks._form')
        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('konteks.show', $konteks) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

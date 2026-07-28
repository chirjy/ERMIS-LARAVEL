@extends('layouts.app')

@section('title', 'Edit RTP - ERMIS BPOM')
@section('page-title', 'Edit Rencana Tindak Pengendalian')

@section('content')
<div class="card p-6 max-w-2xl">
    <form method="POST" action="{{ route('rtp.update', $rtp) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('rtp._form')
        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('rtp.show', $rtp) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

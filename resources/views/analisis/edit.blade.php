@extends('layouts.app')

@section('title', 'Edit Analisis Risiko - ERMIS BPOM')
@section('page-title', 'Edit Analisis Risiko: ' . $risiko->kode_risiko)

@section('content')
<div class="card p-6 max-w-2xl">
    <form method="POST" action="{{ route('identifikasi.analisis.update', [$risiko, $analisis]) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('analisis._form')
        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="{{ route('identifikasi.show', $risiko) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<div class="card p-6 max-w-2xl mt-4">
    <h3 class="font-semibold text-sm text-slate-900 mb-3">Dokumen Pendukung Analisis</h3>
    <p class="text-xs text-slate-500 mb-4">
        Past record, pengalaman relevan, atau literatur terpublikasi yang mendasari penentuan level
        kemungkinan/dampak (BAB V.D.3 Kepka).
    </p>

    @forelse ($analisis->dokumenDukung as $dokumen)
        <div class="flex items-center justify-between px-3 py-2 border-b border-slate-100 last:border-0 text-sm">
            <div>
                <a href="{{ route('dokumen-dukung.download', $dokumen) }}" class="font-medium text-ermis-tealdark hover:underline">{{ $dokumen->nama_file }}</a>
                <div class="text-xs text-slate-400">
                    {{ $dokumen->jenis_dukungan }} · dipakai utk {{ $dokumen->digunakan_untuk }} · diunggah {{ $dokumen->diunggahOleh->nama ?? '—' }}
                </div>
            </div>
            <form method="POST" action="{{ route('dokumen-dukung.destroy', $dokumen) }}" onsubmit="return confirm('Hapus dokumen ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
            </form>
        </div>
    @empty
        <p class="text-sm text-slate-400 mb-4">Belum ada dokumen pendukung.</p>
    @endforelse

    <form method="POST" action="{{ route('dokumen-dukung.store', $analisis) }}" enctype="multipart/form-data" class="mt-4 space-y-3 border-t border-slate-100 pt-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Jenis Dukungan</label>
                <select name="jenis_dukungan" required class="form-input">
                    <option value="PAST_RECORD">Past Record</option>
                    <option value="RELEVANT_EXPERIENCE">Relevant Experience</option>
                    <option value="RELEVANT_PUBLISHED_LITERATURE">Relevant Published Literature</option>
                </select>
            </div>
            <div>
                <label class="form-label">Digunakan Untuk</label>
                <select name="digunakan_untuk" required class="form-input">
                    <option value="KEMUNGKINAN">Kemungkinan</option>
                    <option value="DAMPAK">Dampak</option>
                    <option value="KEDUANYA">Keduanya</option>
                </select>
            </div>
        </div>
        <div>
            <label class="form-label">File (maks 10MB)</label>
            <input type="file" name="file" required class="form-input">
        </div>
        <div>
            <label class="form-label">Keterangan (opsional)</label>
            <input type="text" name="keterangan" class="form-input">
        </div>
        <button type="submit" class="btn-secondary">Unggah Dokumen</button>
    </form>
</div>
@endsection

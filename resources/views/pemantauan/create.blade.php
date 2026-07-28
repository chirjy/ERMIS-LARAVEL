@extends('layouts.app')

@section('title', 'Catat Pemantauan RTP - ERMIS BPOM')
@section('page-title', 'Catat Progress Pemantauan')

@section('content')
<div class="card p-4 mb-4 max-w-2xl bg-slate-50">
    <div class="text-xs text-slate-500 mb-1">RTP untuk:</div>
    <div class="text-sm font-medium">{{ $rtp->analisisRisiko->identifikasiRisiko->kode_risiko }} — {{ $rtp->uraian_mitigasi }}</div>
</div>

<div class="card p-6 max-w-2xl">
    <form method="POST" action="{{ route('rtp.pemantauan.store', $rtp) }}" class="space-y-4">
        @csrf

        <div>
            <label class="form-label">Uraian Target</label>
            <textarea name="uraian_target" rows="2" required class="form-input">{{ old('uraian_target') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">PIC</label>
                <input type="text" name="pic" value="{{ old('pic', $rtp->pic) }}" required class="form-input">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Progress (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="progress_persen" value="{{ old('progress_persen', 0) }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">Tanggal Progress</label>
                <input type="date" name="tanggal_progress" value="{{ old('tanggal_progress', date('Y-m-d')) }}" required class="form-input">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Penilaian Kelemahan Pengendalian (opsional)</label>
                <select name="penilaian_kelemahan_pengendalian" class="form-input">
                    <option value="">—</option>
                    @foreach (['TIDAK_SIGNIFIKAN' => 'Tidak Signifikan', 'SIGNIFIKAN' => 'Signifikan', 'MATERIAL' => 'Material'] as $val => $label)
                        <option value="{{ $val }}" {{ old('penilaian_kelemahan_pengendalian') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Simpulan Efektivitas Pengendalian (opsional)</label>
                <select name="simpulan_efektivitas_pengendalian" class="form-input">
                    <option value="">—</option>
                    @foreach (['EFEKTIF' => 'Efektif', 'TIDAK_EFEKTIF' => 'Tidak Efektif'] as $val => $label)
                        <option value="{{ $val }}" {{ old('simpulan_efektivitas_pengendalian') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="pt-2 flex gap-2">
            <button type="submit" class="btn-primary">Simpan Progress</button>
            <a href="{{ route('rtp.show', $rtp) }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

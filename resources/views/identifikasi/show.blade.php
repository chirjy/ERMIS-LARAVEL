@extends('layouts.app')

@section('title', 'Detail Identifikasi Risiko - ERMIS BPOM')
@section('page-title', 'Detail Identifikasi Risiko')

@section('content')
<div class="max-w-3xl space-y-4">

    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-lg font-semibold text-slate-900">{{ $risiko->kode_risiko }}</div>
                <div class="text-xs text-slate-500">Tahun {{ $risiko->tahun_anggaran }} · Kategori {{ $risiko->kategoriRisiko->kode }} — {{ $risiko->kategoriRisiko->nama }}</div>
            </div>
            @include('partials.status-badge', ['status' => $risiko->status])
        </div>

        @if ($risiko->catatan_penolakan)
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2">
                <strong>Catatan Penolakan:</strong> {{ $risiko->catatan_penolakan }}
            </div>
        @endif

        <dl class="space-y-3 text-sm">
            <div>
                <dt class="font-medium text-slate-700">Pernyataan Risiko</dt>
                <dd class="text-slate-600 whitespace-pre-line">{{ $risiko->pernyataan_risiko }}</dd>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="font-medium text-slate-700">Penyebab Risiko</dt>
                    <dd class="text-slate-600 whitespace-pre-line">{{ $risiko->penyebab_risiko }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-700">Dampak Risiko</dt>
                    <dd class="text-slate-600 whitespace-pre-line">{{ $risiko->dampak_risiko }}</dd>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="font-medium text-slate-700">Sasaran Strategis</dt>
                    <dd class="text-slate-600">{{ $risiko->sasaran_strategis }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-700">Pemilik Risiko</dt>
                    <dd class="text-slate-600">{{ $risiko->pemilikRisiko->nama ?? '—' }}</dd>
                </div>
            </div>
        </dl>

        <div class="mt-5 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
            @can('update', $risiko)
                <a href="{{ route('identifikasi.edit', $risiko) }}" class="btn-secondary">Edit</a>
            @endcan

            @can('ajukanReviu', $risiko)
                <form method="POST" action="{{ route('identifikasi.ajukan-reviu', $risiko) }}">
                    @csrf
                    <button type="submit" class="btn-primary">Ajukan Reviu ke Lini 2</button>
                </form>
            @endcan

            @can('reviewLini2', $risiko)
                <form method="POST" action="{{ route('identifikasi.reviu-lini2', $risiko) }}">
                    @csrf
                    <input type="hidden" name="disetujui" value="1">
                    <button type="submit" class="btn-primary">Setujui Reviu Lini 2</button>
                </form>
                <button type="button" onclick="document.getElementById('modal-tolak-lini2').classList.remove('hidden')" class="btn-danger">Tolak</button>
            @endcan

            @can('approveFinal', $risiko)
                <button type="button" onclick="document.getElementById('modal-approve').classList.remove('hidden')" class="btn-primary">Setujui &amp; TTD Digital</button>
                <button type="button" onclick="document.getElementById('modal-tolak-final').classList.remove('hidden')" class="btn-danger">Tolak</button>
            @endcan
        </div>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-sm text-slate-900">Analisis Risiko</h3>
            @can('manageAnalisis', $risiko)
                <a href="{{ route('identifikasi.analisis.create', $risiko) }}" class="text-xs text-ermis-teal hover:underline">+ Tambah Analisis</a>
            @endcan
        </div>

        @forelse ($risiko->analisisRisikos as $analisis)
            <div class="flex items-center justify-between px-3 py-2 border-b border-slate-100 last:border-0 text-sm">
                <div>
                    <span class="font-medium">{{ $analisis->areaDampak->nama }}</span>
                    <span class="text-slate-400 text-xs ml-2">
                        Inheren: K{{ $analisis->level_kemungkinan_inheren }}×D{{ $analisis->level_dampak_inheren }} → {{ $analisis->level_risiko_inheren }}
                        @if ($analisis->level_risiko_residual)
                            · Residual: {{ $analisis->level_risiko_residual }}
                        @endif
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    @if ($analisis->is_top_risk)
                        <span class="badge bg-red-100 text-red-700">Top Risk</span>
                    @endif
                    @can('manageAnalisis', $risiko)
                        <a href="{{ route('identifikasi.analisis.edit', [$risiko, $analisis]) }}" class="text-ermis-tealdark hover:underline">Edit</a>
                    @endcan
                    @php
                        $levelUntukRtp = $analisis->level_risiko_residual ?? $analisis->level_risiko_inheren;
                        $rtpTerkait = $analisis->rtps->first();
                    @endphp
                    @if ($rtpTerkait)
                        <a href="{{ route('rtp.show', $rtpTerkait) }}" class="text-ermis-tealdark hover:underline">
                            RTP: @include('partials.status-badge', ['status' => $rtpTerkait->status])
                        </a>
                    @elseif ($levelUntukRtp > 15)
                        <a href="{{ route('rtp.create', $analisis) }}" class="text-xs text-red-600 font-medium hover:underline">+ Wajib RTP (Level {{ $levelUntukRtp }})</a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400">Belum ada analisis risiko untuk identifikasi ini.</p>
        @endforelse
    </div>
</div>

{{-- Modal Tolak Lini 2 --}}
<div id="modal-tolak-lini2" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
    <div class="card p-5 w-full max-w-md">
        <h3 class="font-semibold mb-3">Tolak pada Reviu Lini 2</h3>
        <form method="POST" action="{{ route('identifikasi.reviu-lini2', $risiko) }}">
            @csrf
            <input type="hidden" name="disetujui" value="0">
            <label class="form-label">Catatan Penolakan</label>
            <textarea name="catatan" required rows="3" class="form-input mb-4"></textarea>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('modal-tolak-lini2').classList.add('hidden')" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Approve Kepala UPT --}}
<div id="modal-approve" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
    <div class="card p-5 w-full max-w-md">
        <h3 class="font-semibold mb-3">Persetujuan &amp; Tanda Tangan Digital</h3>
        <form method="POST" action="{{ route('identifikasi.approve', $risiko) }}">
            @csrf
            <label class="form-label">Kunci Tanda Tangan (PIN/Password Anda)</label>
            <input type="password" name="signature_key" required class="form-input mb-4">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('modal-approve').classList.add('hidden')" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Setujui &amp; Tandatangani</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tolak Final --}}
<div id="modal-tolak-final" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
    <div class="card p-5 w-full max-w-md">
        <h3 class="font-semibold mb-3">Tolak oleh Kepala UPT</h3>
        <form method="POST" action="{{ route('identifikasi.reject', $risiko) }}">
            @csrf
            <label class="form-label">Catatan Penolakan</label>
            <textarea name="catatan" required rows="3" class="form-input mb-4"></textarea>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('modal-tolak-final').classList.add('hidden')" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection

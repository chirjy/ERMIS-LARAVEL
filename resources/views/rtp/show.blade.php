@extends('layouts.app')

@section('title', 'Detail RTP - ERMIS BPOM')
@section('page-title', 'Detail Rencana Tindak Pengendalian')

@section('content')
<div class="max-w-3xl space-y-4">

    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-lg font-semibold text-slate-900">
                    {{ $rtp->analisisRisiko->identifikasiRisiko->kode_risiko }}
                    <span class="text-sm font-normal text-slate-500">— {{ $rtp->analisisRisiko->areaDampak->nama }}</span>
                </div>
                <div class="text-xs text-slate-500">
                    Level Residual/Inheren: {{ $rtp->analisisRisiko->level_risiko_residual ?? $rtp->analisisRisiko->level_risiko_inheren }}
                    · Dibuat oleh {{ $rtp->createdBy->nama ?? '—' }}
                </div>
            </div>
            @include('partials.status-badge', ['status' => $rtp->status])
        </div>

        @if ($rtp->catatan_penolakan)
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2">
                <strong>Catatan Penolakan:</strong> {{ $rtp->catatan_penolakan }}
            </div>
        @endif

        <dl class="space-y-3 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="font-medium text-slate-700">Opsi Respon Risiko</dt>
                    <dd class="text-slate-600">{{ $rtp->opsi_respon_risiko }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-700">PIC</dt>
                    <dd class="text-slate-600">{{ $rtp->pic }}</dd>
                </div>
            </div>
            <div>
                <dt class="font-medium text-slate-700">Uraian Mitigasi</dt>
                <dd class="text-slate-600 whitespace-pre-line">{{ $rtp->uraian_mitigasi }}</dd>
            </div>
            <div>
                <dt class="font-medium text-slate-700">Output / Target</dt>
                <dd class="text-slate-600 whitespace-pre-line">{{ $rtp->output_target }}</dd>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="font-medium text-slate-700">Sumber Daya Dibutuhkan</dt>
                    <dd class="text-slate-600">{{ $rtp->sumber_daya_dibutuhkan }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-700">Target Waktu Penyelesaian</dt>
                    <dd class="text-slate-600">{{ $rtp->target_waktu_penyelesaian->format('d M Y') }}</dd>
                </div>
            </div>
            @if ($rtp->level_risiko_mitigasi)
                <div>
                    <dt class="font-medium text-slate-700">Target Level Risiko Mitigasi</dt>
                    <dd class="text-slate-600">K{{ $rtp->kemungkinan_mitigasi }}×D{{ $rtp->dampak_mitigasi }} → {{ $rtp->level_risiko_mitigasi }}</dd>
                </div>
            @endif
        </dl>

        <div class="mt-5 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
            @can('update', $rtp)
                <a href="{{ route('rtp.edit', $rtp) }}" class="btn-secondary">Edit</a>
            @endcan

            @can('ajukanReviu', $rtp)
                <form method="POST" action="{{ route('rtp.ajukan-reviu', $rtp) }}">
                    @csrf
                    <button type="submit" class="btn-primary">Ajukan Reviu ke Lini 2</button>
                </form>
            @endcan

            @can('reviewLini2', $rtp)
                <form method="POST" action="{{ route('rtp.reviu-lini2', $rtp) }}">
                    @csrf
                    <input type="hidden" name="disetujui" value="1">
                    <button type="submit" class="btn-primary">Setujui Reviu Lini 2</button>
                </form>
                <button type="button" onclick="document.getElementById('modal-tolak-lini2').classList.remove('hidden')" class="btn-danger">Tolak</button>
            @endcan

            @can('approveFinal', $rtp)
                <button type="button" onclick="document.getElementById('modal-approve').classList.remove('hidden')" class="btn-primary">Setujui &amp; TTD Digital</button>
                <button type="button" onclick="document.getElementById('modal-tolak-final').classList.remove('hidden')" class="btn-danger">Tolak</button>
            @endcan
        </div>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-sm text-slate-900">Riwayat Pemantauan / Reviu</h3>
            @can('catatPemantauan', $rtp)
                <a href="{{ route('rtp.pemantauan.create', $rtp) }}" class="text-xs text-ermis-teal hover:underline">+ Catat Progress</a>
            @endcan
        </div>

        @forelse ($rtp->pemantauans->sortByDesc('tanggal_progress') as $p)
            <div class="px-3 py-2 border-b border-slate-100 last:border-0 text-sm">
                <div class="flex items-center justify-between">
                    <span class="font-medium">{{ $p->tanggal_progress->format('d M Y') }}</span>
                    <span class="badge {{ $p->progress_persen >= 100 ? 'bg-teal-100 text-teal-800' : 'bg-amber-100 text-amber-700' }}">
                        {{ $p->progress_persen }}%
                    </span>
                </div>
                <div class="text-slate-600 mt-1">{{ $p->uraian_target }}</div>
                @if ($p->simpulan_efektivitas_pengendalian)
                    <div class="text-xs text-slate-400 mt-1">
                        Efektivitas: {{ $p->simpulan_efektivitas_pengendalian }}
                        @if ($p->penilaian_kelemahan_pengendalian) · Kelemahan: {{ $p->penilaian_kelemahan_pengendalian }} @endif
                        · dilaporkan {{ $p->dilaporkanOleh->nama ?? '—' }}
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-slate-400">Belum ada catatan pemantauan untuk RTP ini.</p>
        @endforelse
    </div>
</div>

{{-- Modal Tolak Lini 2 --}}
<div id="modal-tolak-lini2" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
    <div class="card p-5 w-full max-w-md">
        <h3 class="font-semibold mb-3">Tolak pada Reviu Lini 2</h3>
        <form method="POST" action="{{ route('rtp.reviu-lini2', $rtp) }}">
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
        <form method="POST" action="{{ route('rtp.approve', $rtp) }}">
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
        <form method="POST" action="{{ route('rtp.reject', $rtp) }}">
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

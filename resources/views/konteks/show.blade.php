@extends('layouts.app')

@section('title', 'Detail Konteks Organisasi - ERMIS BPOM')
@section('page-title', 'Detail Konteks Organisasi')

@section('content')
<div class="max-w-3xl space-y-4">

    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-xs text-slate-500">Tahun Anggaran {{ $konteks->tahun_anggaran }}</div>
                <div class="text-sm text-slate-500">Dibuat oleh {{ $konteks->createdBy->nama ?? '—' }}</div>
            </div>
            @include('partials.status-badge', ['status' => $konteks->status])
        </div>

        @if ($konteks->catatan_penolakan)
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2">
                <strong>Catatan Penolakan:</strong> {{ $konteks->catatan_penolakan }}
            </div>
        @endif

        <dl class="space-y-3 text-sm">
            <div>
                <dt class="font-medium text-slate-700">Ruang Lingkup</dt>
                <dd class="text-slate-600 whitespace-pre-line">{{ $konteks->ruang_lingkup }}</dd>
            </div>
            <div>
                <dt class="font-medium text-slate-700">Sasaran Organisasi</dt>
                <dd class="text-slate-600 whitespace-pre-line">{{ $konteks->sasaran_organisasi }}</dd>
            </div>
            @if ($konteks->stakeholder)
                <div>
                    <dt class="font-medium text-slate-700">Stakeholder</dt>
                    <dd class="text-slate-600"><ul class="list-disc list-inside">@foreach($konteks->stakeholder as $s)<li>{{ $s }}</li>@endforeach</ul></dd>
                </div>
            @endif
            @if ($konteks->peraturan_terkait)
                <div>
                    <dt class="font-medium text-slate-700">Peraturan Terkait</dt>
                    <dd class="text-slate-600"><ul class="list-disc list-inside">@foreach($konteks->peraturan_terkait as $p)<li>{{ $p }}</li>@endforeach</ul></dd>
                </div>
            @endif
        </dl>

        <div class="mt-5 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
            @can('update', $konteks)
                <a href="{{ route('konteks.edit', $konteks) }}" class="btn-secondary">Edit</a>
            @endcan

            @can('ajukanReviu', $konteks)
                <form method="POST" action="{{ route('konteks.ajukan-reviu', $konteks) }}">
                    @csrf
                    <button type="submit" class="btn-primary">Ajukan Reviu ke Lini 2</button>
                </form>
            @endcan

            @can('reviewLini2', $konteks)
                <form method="POST" action="{{ route('konteks.reviu-lini2', $konteks) }}" class="flex items-center gap-2">
                    @csrf
                    <input type="hidden" name="disetujui" value="1">
                    <button type="submit" class="btn-primary">Setujui Reviu Lini 2</button>
                </form>
                <button type="button" onclick="document.getElementById('modal-tolak-lini2').classList.remove('hidden')" class="btn-danger">Tolak</button>
            @endcan

            @can('approveFinal', $konteks)
                <button type="button" onclick="document.getElementById('modal-approve').classList.remove('hidden')" class="btn-primary">Setujui &amp; TTD Digital</button>
                <button type="button" onclick="document.getElementById('modal-tolak-final').classList.remove('hidden')" class="btn-danger">Tolak</button>
            @endcan
        </div>
    </div>

    <div class="card p-5">
        <h3 class="font-semibold text-sm text-slate-900 mb-3">Identifikasi Risiko dari Konteks Ini</h3>
        @forelse ($konteks->identifikasiRisikos as $risiko)
            <a href="{{ route('identifikasi.show', $risiko) }}" class="block px-3 py-2 rounded hover:bg-slate-50 text-sm border-b border-slate-100 last:border-0">
                <span class="font-medium text-ermis-tealdark">{{ $risiko->kode_risiko }}</span> — {{ \Illuminate\Support\Str::limit($risiko->pernyataan_risiko, 80) }}
            </a>
        @empty
            <p class="text-sm text-slate-400">Belum ada identifikasi risiko yang mengacu ke konteks ini.</p>
        @endforelse
    </div>
</div>

{{-- Modal Tolak Lini 2 --}}
<div id="modal-tolak-lini2" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
    <div class="card p-5 w-full max-w-md">
        <h3 class="font-semibold mb-3">Tolak pada Reviu Lini 2</h3>
        <form method="POST" action="{{ route('konteks.reviu-lini2', $konteks) }}">
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
        <form method="POST" action="{{ route('konteks.approve', $konteks) }}">
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
        <form method="POST" action="{{ route('konteks.reject', $konteks) }}">
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

@php $r = $risiko ?? null; @endphp

@if (!$r)
    <div>
        <label class="form-label">Konteks Organisasi (harus sudah Disetujui Kepala UPT)</label>
        <select name="konteks_id" required class="form-input">
            <option value="">— Pilih Konteks Organisasi —</option>
            @foreach ($kontekses as $konteks)
                <option value="{{ $konteks->id }}" {{ old('konteks_id') == $konteks->id ? 'selected' : '' }}>
                    {{ $konteks->tahun_anggaran }} — {{ \Illuminate\Support\Str::limit($konteks->ruang_lingkup, 60) }}
                </option>
            @endforeach
        </select>
        @if ($kontekses->isEmpty())
            <p class="text-xs text-amber-600 mt-1">Belum ada Konteks Organisasi berstatus "Disetujui Kepala UPT" untuk UPT Anda. Selesaikan alur Konteks Organisasi terlebih dahulu.</p>
        @endif
    </div>
@else
    <input type="hidden" name="konteks_id" value="{{ $r->konteks_id }}">
    <div class="text-xs text-slate-500">Konteks Organisasi: Tahun {{ $r->konteks->tahun_anggaran }} (tidak dapat diubah)</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Tahun Anggaran</label>
        <input type="number" name="tahun_anggaran" value="{{ old('tahun_anggaran', $r?->tahun_anggaran ?? date('Y')) }}" required class="form-input">
    </div>
    <div>
        <label class="form-label">Kode Risiko</label>
        <input type="text" name="kode_risiko" value="{{ old('kode_risiko', $r?->kode_risiko) }}" required maxlength="30" class="form-input" placeholder="mis. OP-01">
        <p class="text-xs text-slate-400 mt-1">Unik per UPT &amp; tahun anggaran, bukan unik nasional.</p>
    </div>
</div>

<div>
    <label class="form-label">Sasaran Strategis</label>
    <textarea name="sasaran_strategis" rows="2" required class="form-input">{{ old('sasaran_strategis', $r?->sasaran_strategis) }}</textarea>
</div>

<div>
    <label class="form-label">Indikator Kinerja</label>
    <textarea name="indikator_kinerja" rows="2" required class="form-input">{{ old('indikator_kinerja', $r?->indikator_kinerja) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Isu</label>
        <select name="isu" required class="form-input">
            @foreach (['INTERNAL' => 'Internal', 'EKSTERNAL' => 'Eksternal'] as $val => $label)
                <option value="{{ $val }}" {{ old('isu', $r?->isu) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Jenis Risiko</label>
        <select name="jenis_risiko" required class="form-input">
            @foreach (['DOWNSIDE' => 'Downside', 'UPSIDE' => 'Upside'] as $val => $label)
                <option value="{{ $val }}" {{ old('jenis_risiko', $r?->jenis_risiko ?? 'DOWNSIDE') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="form-label">Kegiatan / Proses Bisnis</label>
    <textarea name="kegiatan_proses_bisnis" rows="2" required class="form-input">{{ old('kegiatan_proses_bisnis', $r?->kegiatan_proses_bisnis) }}</textarea>
</div>

<div>
    <label class="form-label">Kategori Risiko</label>
    <select name="kategori_risiko_id" required class="form-input">
        <option value="">— Pilih Kategori —</option>
        @foreach ($kategoris as $kategori)
            <option value="{{ $kategori->id }}" {{ old('kategori_risiko_id', $r?->kategori_risiko_id) == $kategori->id ? 'selected' : '' }}>
                {{ $kategori->kode }} — {{ $kategori->nama }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="form-label">
        Pernyataan Risiko
        <span class="text-xs font-normal text-slate-400">(kondisi peristiwa, hindari mencampur penyebab/dampak — BAB IV.D.2)</span>
    </label>
    <textarea id="pernyataan_risiko" name="pernyataan_risiko" rows="3" required class="form-input">{{ old('pernyataan_risiko', $r?->pernyataan_risiko) }}</textarea>
    <div id="validasi-pernyataan-hasil" class="mt-2 space-y-1"></div>
</div>

<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const textarea = document.getElementById('pernyataan_risiko');
    const sasaranEl = document.querySelector('[name="sasaran_strategis"]');
    const hasilEl = document.getElementById('validasi-pernyataan-hasil');
    if (!textarea) return;

    let timer;
    textarea.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(cekValidasi, 600);
    });

    async function cekValidasi() {
        const teks = textarea.value.trim();
        if (!teks) { hasilEl.innerHTML = ''; return; }

        try {
            const res = await fetch('{{ route("identifikasi.preview-validasi-pernyataan") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ pernyataan_risiko: teks, sasaran_strategis: sasaranEl ? sasaranEl.value : '' }),
            });
            if (!res.ok) return;
            const data = await res.json();
            hasilEl.innerHTML = '';
            data.pelanggaran.forEach(p => {
                const warna = p.tingkat_pelanggaran === 'ALERT_BLOKIR' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-amber-50 border-amber-200 text-amber-700';
                const div = document.createElement('div');
                div.className = `text-xs rounded border px-2 py-1.5 ${warna}`;
                div.innerHTML = `<strong>${p.kode_kaidah} — ${p.judul}:</strong> ${p.pesan}`;
                hasilEl.appendChild(div);
            });
        } catch (e) { /* diamkan, validasi bersifat advisory */ }
    }
})();
</script>

<div>
    <label class="form-label">Penyebab Risiko</label>
    <textarea name="penyebab_risiko" rows="2" required class="form-input">{{ old('penyebab_risiko', $r?->penyebab_risiko) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Sumber Risiko</label>
        <select name="sumber_risiko" required class="form-input">
            @foreach (['INTERNAL' => 'Internal', 'EKSTERNAL' => 'Eksternal'] as $val => $label)
                <option value="{{ $val }}" {{ old('sumber_risiko', $r?->sumber_risiko) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Pemilik Risiko</label>
        <select name="pemilik_risiko_id" required class="form-input">
            <option value="">— Pilih Pemilik Risiko —</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('pemilik_risiko_id', $r?->pemilik_risiko_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="form-label">Dampak Risiko</label>
    <textarea name="dampak_risiko" rows="2" required class="form-input">{{ old('dampak_risiko', $r?->dampak_risiko) }}</textarea>
</div>

<div>
    <label class="form-label">Pihak Terkait (satu per baris, opsional)</label>
    <textarea name="pihak_terkait_text" rows="2" class="form-input">{{ old('pihak_terkait_text', $r ? implode("\n", $r->pihak_terkait ?? []) : '') }}</textarea>
</div>

<script>
document.currentScript.closest('form')?.addEventListener('submit', function () {
    const ta = this.querySelector('[name="pihak_terkait_text"]');
    if (!ta) return;
    ta.value.split('\n').map(s => s.trim()).filter(Boolean).forEach(line => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'pihak_terkait[]';
        input.value = line;
        this.appendChild(input);
    });
});
</script>

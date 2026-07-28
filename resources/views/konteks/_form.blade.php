@php $k = $konteks ?? null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Tahun Anggaran</label>
        <input type="number" name="tahun_anggaran" value="{{ old('tahun_anggaran', $k?->tahun_anggaran ?? date('Y')) }}" required class="form-input">
    </div>
</div>

<div>
    <label class="form-label">Ruang Lingkup</label>
    <textarea name="ruang_lingkup" rows="3" required class="form-input">{{ old('ruang_lingkup', $k?->ruang_lingkup) }}</textarea>
</div>

<div>
    <label class="form-label">Sasaran Organisasi</label>
    <textarea name="sasaran_organisasi" rows="3" required class="form-input">{{ old('sasaran_organisasi', $k?->sasaran_organisasi) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Stakeholder (satu per baris)</label>
        <textarea name="stakeholder_text" rows="4" class="form-input" placeholder="Dinas Kesehatan Kab.&#10;Pelaku usaha farmasi&#10;...">{{ old('stakeholder_text', $k ? implode("\n", $k->stakeholder ?? []) : '') }}</textarea>
    </div>
    <div>
        <label class="form-label">Peraturan Terkait (satu per baris)</label>
        <textarea name="peraturan_terkait_text" rows="4" class="form-input" placeholder="UU 36/2009 Kesehatan&#10;PP 28/2004&#10;...">{{ old('peraturan_terkait_text', $k ? implode("\n", $k->peraturan_terkait ?? []) : '') }}</textarea>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Kriteria Kemungkinan Custom (opsional)</label>
        <textarea name="kriteria_kemungkinan_custom" rows="2" class="form-input">{{ old('kriteria_kemungkinan_custom', $k?->kriteria_kemungkinan_custom) }}</textarea>
    </div>
    <div>
        <label class="form-label">Kriteria Dampak Custom (opsional)</label>
        <textarea name="kriteria_dampak_custom" rows="2" class="form-input">{{ old('kriteria_dampak_custom', $k?->kriteria_dampak_custom) }}</textarea>
    </div>
</div>

<script>
// Ubah textarea baris-per-baris menjadi array JSON sebelum submit,
// supaya controller menerima 'stakeholder'/'peraturan_terkait' sbg array sesuai StoreKonteksOrganisasiRequest.
document.currentScript.closest('form')?.addEventListener('submit', function () {
    const toArrayField = (textName, hiddenName) => {
        const ta = this.querySelector(`[name="${textName}"]`);
        if (!ta) return;
        const lines = ta.value.split('\n').map(s => s.trim()).filter(Boolean);
        lines.forEach(line => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${hiddenName}[]`;
            input.value = line;
            this.appendChild(input);
        });
    };
    toArrayField('stakeholder_text', 'stakeholder');
    toArrayField('peraturan_terkait_text', 'peraturan_terkait');
});
</script>

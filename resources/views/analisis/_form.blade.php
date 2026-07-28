@php $a = $analisis ?? null; @endphp

<div>
    <label class="form-label">Area Dampak</label>
    <select name="area_dampak_id" required class="form-input">
        <option value="">— Pilih Area Dampak —</option>
        @foreach ($areaDampaks as $area)
            <option value="{{ $area->id }}" {{ old('area_dampak_id', $a?->area_dampak_id) == $area->id ? 'selected' : '' }}>
                {{ $area->nama }} ({{ $area->jenis_risiko }})
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="form-label">Level Kemungkinan Inheren (1-5)</label>
        <input type="number" min="1" max="5" id="level_kemungkinan_inheren" name="level_kemungkinan_inheren"
               value="{{ old('level_kemungkinan_inheren', $a?->level_kemungkinan_inheren ?? 1) }}" required class="form-input">
    </div>
    <div>
        <label class="form-label">Level Dampak Inheren (1-5)</label>
        <input type="number" min="1" max="5" id="level_dampak_inheren" name="level_dampak_inheren"
               value="{{ old('level_dampak_inheren', $a?->level_dampak_inheren ?? 1) }}" required class="form-input">
    </div>
</div>

<div id="preview-inheren" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm flex items-center gap-2">
    <span class="text-slate-500">Level Risiko Inheren:</span>
    <span id="preview-inheren-badge" class="badge bg-slate-200 text-slate-600">menghitung...</span>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="form-label">Metode Penentuan Kemungkinan (opsional)</label>
        <select name="metode_penentuan_kemungkinan_id" class="form-input">
            <option value="">—</option>
            @foreach ($metodePenilaians->whereIn('cocok_untuk', ['KEMUNGKINAN', 'KEDUANYA']) as $metode)
                <option value="{{ $metode->id }}" {{ old('metode_penentuan_kemungkinan_id', $a?->metode_penentuan_kemungkinan_id) == $metode->id ? 'selected' : '' }}>{{ $metode->nama }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Metode Penentuan Dampak (opsional)</label>
        <select name="metode_penentuan_dampak_id" class="form-input">
            <option value="">—</option>
            @foreach ($metodePenilaians->whereIn('cocok_untuk', ['DAMPAK', 'KEDUANYA']) as $metode)
                <option value="{{ $metode->id }}" {{ old('metode_penentuan_dampak_id', $a?->metode_penentuan_dampak_id) == $metode->id ? 'selected' : '' }}>{{ $metode->nama }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="form-label">Pendekatan Penentuan Kemungkinan (opsional)</label>
    <select name="pendekatan_kemungkinan" class="form-input">
        <option value="">—</option>
        <option value="JUMLAH_FREKUENSI" {{ old('pendekatan_kemungkinan', $a?->pendekatan_kemungkinan) === 'JUMLAH_FREKUENSI' ? 'selected' : '' }}>Jumlah Frekuensi</option>
        <option value="PROBABILITAS" {{ old('pendekatan_kemungkinan', $a?->pendekatan_kemungkinan) === 'PROBABILITAS' ? 'selected' : '' }}>Probabilitas</option>
    </select>
</div>

<div>
    <label class="form-label">Uraian Dasar Pertimbangan (opsional)</label>
    <textarea name="uraian_dasar_pertimbangan" rows="2" class="form-input">{{ old('uraian_dasar_pertimbangan', $a?->uraian_dasar_pertimbangan) }}</textarea>
</div>

<div>
    <label class="form-label">Aktivitas Pengendalian Saat Ini</label>
    <textarea name="aktivitas_pengendalian" rows="2" class="form-input">{{ old('aktivitas_pengendalian', $a?->aktivitas_pengendalian) }}</textarea>
</div>

<div>
    <label class="form-label">Atribut Pengendalian</label>
    <textarea name="atribut_pengendalian" rows="2" class="form-input">{{ old('atribut_pengendalian', $a?->atribut_pengendalian) }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="form-label">Penilaian Kelemahan Pengendalian</label>
        <textarea name="penilaian_kelemahan_pengendalian" rows="2" class="form-input">{{ old('penilaian_kelemahan_pengendalian', $a?->penilaian_kelemahan_pengendalian) }}</textarea>
    </div>
    <div>
        <label class="form-label">Simpulan Efektivitas Pengendalian</label>
        <select name="simpulan_efektivitas_pengendalian" class="form-input">
            <option value="">—</option>
            @foreach (['EFEKTIF' => 'Efektif', 'TIDAK_EFEKTIF' => 'Tidak Efektif'] as $val => $label)
                <option value="{{ $val }}" {{ old('simpulan_efektivitas_pengendalian', $a?->simpulan_efektivitas_pengendalian) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="form-label">Level Kemungkinan Residual (opsional)</label>
        <input type="number" min="1" max="5" id="level_kemungkinan_residual" name="level_kemungkinan_residual"
               value="{{ old('level_kemungkinan_residual', $a?->level_kemungkinan_residual) }}" class="form-input">
    </div>
    <div>
        <label class="form-label">Level Dampak Residual (opsional)</label>
        <input type="number" min="1" max="5" id="level_dampak_residual" name="level_dampak_residual"
               value="{{ old('level_dampak_residual', $a?->level_dampak_residual) }}" class="form-input">
    </div>
</div>

<div id="preview-residual" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm flex items-center gap-2">
    <span class="text-slate-500">Level Risiko Residual:</span>
    <span id="preview-residual-badge" class="badge bg-slate-200 text-slate-600">isi kedua level residual</span>
</div>

<label class="flex items-center gap-2 text-sm text-slate-600">
    <input type="checkbox" name="is_top_risk" value="1" {{ old('is_top_risk', $a?->is_top_risk) ? 'checked' : '' }} class="rounded border-slate-300 text-ermis-teal">
    Tandai sebagai Top Risk (prioritas RTP)
</label>

<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    async function preview(kInput, dInput, badgeEl, allowEmpty) {
        const k = kInput.value, d = dInput.value;
        if (allowEmpty && (!k || !d)) {
            badgeEl.textContent = 'isi kedua level residual';
            badgeEl.className = 'badge bg-slate-200 text-slate-600';
            return;
        }
        if (!k || !d) return;

        try {
            const res = await fetch('{{ route('analisis.preview') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ level_kemungkinan: k, level_dampak: d }),
            });
            if (!res.ok) throw new Error('preview gagal');
            const data = await res.json();
            badgeEl.textContent = `${data.label} (${data.besaran_risiko})`;
            badgeEl.style.backgroundColor = data.warna_hex + '22';
            badgeEl.style.color = data.warna_hex;
        } catch (e) {
            badgeEl.textContent = 'kombinasi tidak valid';
            badgeEl.className = 'badge bg-red-100 text-red-700';
        }
    }

    const ki = document.getElementById('level_kemungkinan_inheren');
    const di = document.getElementById('level_dampak_inheren');
    const bi = document.getElementById('preview-inheren-badge');
    [ki, di].forEach(el => el.addEventListener('input', () => preview(ki, di, bi, false)));
    preview(ki, di, bi, false);

    const kr = document.getElementById('level_kemungkinan_residual');
    const dr = document.getElementById('level_dampak_residual');
    const br = document.getElementById('preview-residual-badge');
    [kr, dr].forEach(el => el.addEventListener('input', () => preview(kr, dr, br, true)));
    preview(kr, dr, br, true);
})();
</script>

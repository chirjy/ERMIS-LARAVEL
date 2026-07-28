@php $r = $rtp ?? null; @endphp

<div>
    <label class="form-label">Opsi Respon Risiko</label>
    <select name="opsi_respon_risiko" required class="form-input">
        @foreach (['AVOID' => 'Avoid (Menghindari)', 'REDUCE' => 'Reduce (Mereduksi)', 'SHARE' => 'Share (Membagi)', 'ACCEPT' => 'Accept (Menerima)'] as $val => $label)
            <option value="{{ $val }}" {{ old('opsi_respon_risiko', $r?->opsi_respon_risiko) === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="form-label">Uraian Mitigasi (RTP)</label>
    <textarea name="uraian_mitigasi" rows="3" required class="form-input">{{ old('uraian_mitigasi', $r?->uraian_mitigasi) }}</textarea>
</div>

<div>
    <label class="form-label">Output / Target</label>
    <textarea name="output_target" rows="2" required class="form-input">{{ old('output_target', $r?->output_target) }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="form-label">PIC</label>
        <input type="text" name="pic" value="{{ old('pic', $r?->pic) }}" required class="form-input">
    </div>
    <div>
        <label class="form-label">Target Waktu Penyelesaian</label>
        <input type="date" name="target_waktu_penyelesaian"
               value="{{ old('target_waktu_penyelesaian', $r?->target_waktu_penyelesaian?->format('Y-m-d')) }}" required class="form-input">
    </div>
</div>

<div>
    <label class="form-label">Sumber Daya yang Dibutuhkan</label>
    <textarea name="sumber_daya_dibutuhkan" rows="2" required class="form-input">{{ old('sumber_daya_dibutuhkan', $r?->sumber_daya_dibutuhkan) }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="form-label">Kemungkinan Mitigasi (target, opsional)</label>
        <input type="number" min="1" max="5" name="kemungkinan_mitigasi" value="{{ old('kemungkinan_mitigasi', $r?->kemungkinan_mitigasi) }}" class="form-input">
    </div>
    <div>
        <label class="form-label">Dampak Mitigasi (target, opsional)</label>
        <input type="number" min="1" max="5" name="dampak_mitigasi" value="{{ old('dampak_mitigasi', $r?->dampak_mitigasi) }}" class="form-input">
    </div>
</div>
<p class="text-xs text-slate-400">Jika keduanya diisi, level risiko mitigasi akan dihitung otomatis dari matriks Kepka saat disimpan.</p>

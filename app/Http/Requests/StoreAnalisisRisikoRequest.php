<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnalisisRisikoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'area_dampak_id' => ['required', 'exists:ref_area_dampaks,id'],
            'level_kemungkinan_inheren' => ['required', 'integer', 'between:1,5'],
            'level_dampak_inheren' => ['required', 'integer', 'between:1,5'],
            'metode_penentuan_kemungkinan_id' => ['nullable', 'exists:ref_metode_penilaians,id'],
            'metode_penentuan_dampak_id' => ['nullable', 'exists:ref_metode_penilaians,id'],
            'uraian_dasar_pertimbangan' => ['nullable', 'string'],
            'pendekatan_kemungkinan' => ['nullable', 'in:JUMLAH_FREKUENSI,PROBABILITAS'],
            'aktivitas_pengendalian' => ['nullable', 'string'],
            'atribut_pengendalian' => ['nullable', 'string'],
            'penilaian_kelemahan_pengendalian' => ['nullable', 'string'],
            'simpulan_efektivitas_pengendalian' => ['nullable', 'in:EFEKTIF,TIDAK_EFEKTIF'],
            'level_kemungkinan_residual' => ['nullable', 'integer', 'between:1,5'],
            'level_dampak_residual' => ['nullable', 'integer', 'between:1,5'],
            'is_top_risk' => ['nullable', 'boolean'],
        ];
    }
}

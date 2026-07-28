<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKonteksOrganisasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // otorisasi UPT & peran dicek di controller/policy
    }

    public function rules(): array
    {
        return [
            'tahun_anggaran' => ['required', 'digits:4'],
            'ruang_lingkup' => ['required', 'string'],
            'sasaran_organisasi' => ['required', 'string'],
            'stakeholder' => ['nullable', 'array'],
            'stakeholder.*' => ['string'],
            'peraturan_terkait' => ['nullable', 'array'],
            'peraturan_terkait.*' => ['string'],
            'kriteria_kemungkinan_custom' => ['nullable', 'string'],
            'kriteria_dampak_custom' => ['nullable', 'string'],
        ];
    }
}

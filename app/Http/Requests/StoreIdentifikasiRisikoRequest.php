<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIdentifikasiRisikoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'konteks_id' => ['required', 'uuid', 'exists:trn_konteks_organisasis,id'],
            'tahun_anggaran' => ['required', 'digits:4'],
            'sasaran_strategis' => ['required', 'string'],
            'indikator_kinerja' => ['required', 'string'],
            'isu' => ['required', 'in:INTERNAL,EKSTERNAL'],
            'kegiatan_proses_bisnis' => ['required', 'string'],
            'kode_risiko' => ['required', 'string', 'max:30'],
            'kategori_risiko_id' => ['required', 'exists:ref_kategori_risikos,id'],
            'jenis_risiko' => ['required', 'in:DOWNSIDE,UPSIDE'],
            'pernyataan_risiko' => ['required', 'string'],
            'penyebab_risiko' => ['required', 'string'],
            'sumber_risiko' => ['required', 'in:INTERNAL,EKSTERNAL'],
            'dampak_risiko' => ['required', 'string'],
            'pemilik_risiko_id' => ['required', 'uuid', 'exists:sys_users,id'],
            'pihak_terkait' => ['nullable', 'array'],
            'pihak_terkait.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_risiko.required' => 'Kode risiko wajib diisi (unik per UPT & tahun anggaran).',
        ];
    }
}

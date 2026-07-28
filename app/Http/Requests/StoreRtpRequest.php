<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'opsi_respon_risiko' => ['required', 'in:AVOID,REDUCE,SHARE,ACCEPT'],
            'uraian_mitigasi' => ['required', 'string'],
            'output_target' => ['required', 'string'],
            'pic' => ['required', 'string', 'max:150'],
            'sumber_daya_dibutuhkan' => ['required', 'string'],
            'target_waktu_penyelesaian' => ['required', 'date'],
            'kemungkinan_mitigasi' => ['nullable', 'integer', 'between:1,5'],
            'dampak_mitigasi' => ['nullable', 'integer', 'between:1,5'],
        ];
    }
}

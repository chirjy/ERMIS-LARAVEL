<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemantauanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uraian_target' => ['required', 'string'],
            'due_date' => ['required', 'date'],
            'pic' => ['required', 'string', 'max:150'],
            'progress_persen' => ['required', 'numeric', 'between:0,100'],
            'tanggal_progress' => ['required', 'date'],
            'penilaian_kelemahan_pengendalian' => ['nullable', 'in:TIDAK_SIGNIFIKAN,SIGNIFIKAN,MATERIAL'],
            'simpulan_efektivitas_pengendalian' => ['nullable', 'in:EFEKTIF,TIDAK_EFEKTIF'],
        ];
    }
}

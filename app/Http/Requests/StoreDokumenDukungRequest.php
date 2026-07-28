<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDokumenDukungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_dukungan' => ['required', 'in:PAST_RECORD,RELEVANT_EXPERIENCE,RELEVANT_PUBLISHED_LITERATURE'],
            'digunakan_untuk' => ['required', 'in:KEMUNGKINAN,DAMPAK,KEDUANYA'],
            'file' => ['required', 'file', 'max:10240'], // maks 10MB
            'keterangan' => ['nullable', 'string'],
        ];
    }
}

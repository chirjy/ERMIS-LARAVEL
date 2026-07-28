<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDokumenDukungRequest;
use App\Models\TrnAnalisisRisiko;
use App\Models\TrnDokumenDukungAnalisis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenDukungController extends Controller
{
    public function store(StoreDokumenDukungRequest $request, TrnAnalisisRisiko $analisis)
    {
        $this->authorize('manageAnalisis', $analisis->identifikasiRisiko);

        $file = $request->file('file');
        $path = $file->store('dokumen-dukung-analisis', 'local');

        TrnDokumenDukungAnalisis::create([
            'analisis_risiko_id' => $analisis->id,
            'jenis_dukungan' => $request->input('jenis_dukungan'),
            'digunakan_untuk' => $request->input('digunakan_untuk'),
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'mime_type' => $file->getClientMimeType(),
            'ukuran_bytes' => $file->getSize(),
            'keterangan' => $request->input('keterangan'),
            'diunggah_oleh' => Auth::id(),
        ]);

        return back()->with('status', 'Dokumen pendukung berhasil diunggah.');
    }

    public function download(TrnDokumenDukungAnalisis $dokumen)
    {
        $this->authorize('manageAnalisis', $dokumen->analisisRisiko->identifikasiRisiko);

        return Storage::disk('local')->download($dokumen->path_file, $dokumen->nama_file);
    }

    public function destroy(TrnDokumenDukungAnalisis $dokumen)
    {
        $this->authorize('manageAnalisis', $dokumen->analisisRisiko->identifikasiRisiko);

        Storage::disk('local')->delete($dokumen->path_file);
        $dokumen->delete();

        return back()->with('status', 'Dokumen pendukung dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnalisisRisikoRequest;
use App\Models\RefAreaDampak;
use App\Models\RefMetodePenilaian;
use App\Models\TrnAnalisisRisiko;
use App\Models\TrnIdentifikasiRisiko;
use App\Services\RiskEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalisisRisikoController extends Controller
{
    public function __construct(protected RiskEngineService $riskEngine)
    {
    }

    public function create(TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('manageAnalisis', $risiko);
        $areaDampaks = RefAreaDampak::orderBy('prioritas')->get();
        $metodePenilaians = RefMetodePenilaian::orderBy('kode')->get();

        return view('analisis.create', compact('risiko', 'areaDampaks', 'metodePenilaians'));
    }

    public function store(StoreAnalisisRisikoRequest $request, TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('manageAnalisis', $risiko);

        $data = $request->validated();
        $data['identifikasi_risiko_id'] = $risiko->id;
        $data['created_by'] = Auth::id();

        $analisis = new TrnAnalisisRisiko($data);
        $this->riskEngine->isiLevelInheren($analisis);
        $this->riskEngine->isiLevelResidual($analisis);
        $analisis->save();

        return redirect()->route('identifikasi.show', $risiko)
            ->with('status', 'Analisis Risiko tersimpan. Level risiko dihitung otomatis dari matriks Kepka.');
    }

    public function edit(TrnIdentifikasiRisiko $risiko, TrnAnalisisRisiko $analisis)
    {
        $this->authorize('manageAnalisis', $risiko);
        $areaDampaks = RefAreaDampak::orderBy('prioritas')->get();
        $metodePenilaians = RefMetodePenilaian::orderBy('kode')->get();
        $analisis->load('dokumenDukung.diunggahOleh');

        return view('analisis.edit', compact('risiko', 'analisis', 'areaDampaks', 'metodePenilaians'));
    }

    public function update(StoreAnalisisRisikoRequest $request, TrnIdentifikasiRisiko $risiko, TrnAnalisisRisiko $analisis)
    {
        $this->authorize('manageAnalisis', $risiko);

        $analisis->fill($request->validated());
        $this->riskEngine->isiLevelInheren($analisis);
        $this->riskEngine->isiLevelResidual($analisis);
        $analisis->save();

        return redirect()->route('identifikasi.show', $risiko)->with('status', 'Analisis Risiko diperbarui.');
    }

    /** Dipanggil AJAX dari form (on-change) supaya badge level tampil real-time sebelum disimpan */
    public function preview(Request $request)
    {
        $request->validate([
            'level_kemungkinan' => ['required', 'integer', 'between:1,5'],
            'level_dampak' => ['required', 'integer', 'between:1,5'],
        ]);

        $hasil = $this->riskEngine->hitungLevel(
            (int) $request->input('level_kemungkinan'),
            (int) $request->input('level_dampak')
        );

        return response()->json($hasil);
    }
}

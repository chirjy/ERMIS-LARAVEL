<?php

namespace App\Http\Controllers;

use App\Models\RefAreaDampak;
use App\Models\RefIstilahGlosarium;
use App\Models\RefKaidahPernyataanRisiko;
use App\Models\RefKriteriaDampak;
use App\Models\RefKriteriaKemungkinan;
use App\Models\RefMetodePenilaian;
use App\Services\PernyataanRisikoValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    public function glosarium(Request $request)
    {
        $q = $request->input('q');

        $istilahs = RefIstilahGlosarium::query()
            ->when($q, fn ($query) => $query->where('istilah', 'like', "%{$q}%")->orWhere('definisi', 'like', "%{$q}%"))
            ->orderBy('nomor_urut')
            ->get();

        return view('knowledge-base.glosarium', compact('istilahs', 'q'));
    }

    public function kriteria()
    {
        $kemungkinans = RefKriteriaKemungkinan::orderBy('level')->get();

        $areaDampaks = RefAreaDampak::where('jenis_risiko', 'DOWNSIDE')->orderBy('prioritas')->get();
        $kriteriaDampak = RefKriteriaDampak::with('areaDampak')->orderBy('area_dampak_id')->orderBy('tingkatan_upr')->orderBy('level')->get()
            ->groupBy('area_dampak_id');

        $metodePenilaians = RefMetodePenilaian::orderBy('kode')->get();

        return view('knowledge-base.kriteria', compact('kemungkinans', 'areaDampaks', 'kriteriaDampak', 'metodePenilaians'));
    }

    public function kaidah()
    {
        $kaidahs = RefKaidahPernyataanRisiko::orderBy('kode_kaidah')->get();

        // Ringkasan kepatuhan K02 (minimal 1 downside per sasaran/indikator) untuk UPT pengguna saat ini
        $tahunIni = (int) date('Y');
        $tanpaDownside = app(PernyataanRisikoValidationService::class)
            ->cekMinimalDownside(Auth::user()->upt_id, $tahunIni);

        return view('knowledge-base.kaidah', compact('kaidahs', 'tanpaDownside', 'tahunIni'));
    }
}

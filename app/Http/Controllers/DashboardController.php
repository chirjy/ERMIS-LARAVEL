<?php

namespace App\Http\Controllers;

use App\Models\TrnIdentifikasiRisiko;
use App\Models\TrnKonteksOrganisasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $uptId = Auth::user()->upt_id;

        $ringkasan = [
            'konteks_draft' => TrnKonteksOrganisasi::where('upt_id', $uptId)->where('status', 'DRAFT')->count(),
            'risiko_draft' => TrnIdentifikasiRisiko::where('upt_id', $uptId)->where('status', 'DRAFT')->count(),
            'risiko_menunggu_reviu' => TrnIdentifikasiRisiko::where('upt_id', $uptId)
                ->where('status', 'DIAJUKAN_REVIU_LINI2')->count(),
            'risiko_disetujui' => TrnIdentifikasiRisiko::where('upt_id', $uptId)
                ->where('status', 'DISETUJUI_KEPALA_UPT')->count(),
        ];

        $risikoTerbaru = TrnIdentifikasiRisiko::where('upt_id', $uptId)
            ->with(['kategoriRisiko', 'analisisTerakhir'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('ringkasan', 'risikoTerbaru'));
    }
}

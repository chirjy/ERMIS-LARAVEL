<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRtpRequest;
use App\Models\TrnAnalisisRisiko;
use App\Models\TrnRencanaTindakPengendalian;
use App\Policies\RtpPolicy;
use App\Services\RiskEngineService;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RtpController extends Controller
{
    public function __construct(
        protected WorkflowService $workflow,
        protected RiskEngineService $riskEngine,
        protected RtpPolicy $rtpPolicy
    ) {
    }

    public function create(TrnAnalisisRisiko $analisis)
    {
        abort_unless($this->rtpPolicy->bolehBuatRtp(Auth::user(), $analisis), 403);

        return view('rtp.create', compact('analisis'));
    }

    public function store(StoreRtpRequest $request, TrnAnalisisRisiko $analisis)
    {
        abort_unless($this->rtpPolicy->bolehBuatRtp(Auth::user(), $analisis), 403);

        $data = $request->validated();
        $data['analisis_risiko_id'] = $analisis->id;
        $data['upt_id'] = $analisis->identifikasiRisiko->upt_id;
        $data['created_by'] = Auth::id();

        $rtp = new TrnRencanaTindakPengendalian($data);

        // Level risiko mitigasi dihitung otomatis dari matriks yang sama (jika kedua level diisi)
        if ($rtp->kemungkinan_mitigasi && $rtp->dampak_mitigasi) {
            $hasil = $this->riskEngine->hitungLevel($rtp->kemungkinan_mitigasi, $rtp->dampak_mitigasi);
            $rtp->level_risiko_mitigasi = $hasil['besaran_risiko'];
        }

        $rtp->save();

        return redirect()->route('rtp.show', $rtp)
            ->with('status', 'Rencana Tindak Pengendalian (RTP) berhasil disimpan sebagai draft.');
    }

    public function show(TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('view', $rtp);
        $rtp->load(['analisisRisiko.identifikasiRisiko', 'analisisRisiko.areaDampak', 'createdBy', 'pemantauans.dilaporkanOleh']);

        return view('rtp.show', compact('rtp'));
    }

    public function edit(TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('update', $rtp);

        return view('rtp.edit', compact('rtp'));
    }

    public function update(StoreRtpRequest $request, TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('update', $rtp);

        $rtp->fill($request->validated());

        if ($rtp->kemungkinan_mitigasi && $rtp->dampak_mitigasi) {
            $hasil = $this->riskEngine->hitungLevel($rtp->kemungkinan_mitigasi, $rtp->dampak_mitigasi);
            $rtp->level_risiko_mitigasi = $hasil['besaran_risiko'];
        }

        $rtp->save();

        return redirect()->route('rtp.show', $rtp)->with('status', 'RTP diperbarui.');
    }

    public function destroy(TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('delete', $rtp);
        $rtp->delete();

        return redirect()->route('identifikasi.show', $rtp->analisisRisiko->identifikasiRisiko)
            ->with('status', 'RTP dihapus.');
    }

    public function ajukanReviu(TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('ajukanReviu', $rtp);
        $this->workflow->ajukanReviuLini2($rtp);

        return back()->with('status', 'RTP diajukan untuk reviu Lini 2.');
    }

    public function reviuLini2(Request $request, TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('reviewLini2', $rtp);
        $request->validate(['disetujui' => ['required', 'boolean'], 'catatan' => ['nullable', 'string']]);

        $this->workflow->reviuLini2(
            $rtp,
            Auth::user(),
            $request->boolean('disetujui'),
            $request->input('catatan')
        );

        return back()->with('status', $request->boolean('disetujui')
            ? 'RTP disetujui pada reviu Lini 2.'
            : 'RTP ditolak pada reviu Lini 2.');
    }

    public function approve(Request $request, TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('approveFinal', $rtp);
        $request->validate(['signature_key' => ['required', 'string']]);

        $this->workflow->approveKepalaUpt($rtp, Auth::user(), $request->input('signature_key'));

        return back()->with('status', 'RTP disetujui & ditandatangani secara digital.');
    }

    public function reject(Request $request, TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('approveFinal', $rtp);
        $request->validate(['catatan' => ['required', 'string']]);

        $this->workflow->rejectKepalaUpt($rtp, $request->input('catatan'));

        return back()->with('status', 'RTP ditolak oleh Kepala UPR.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKonteksOrganisasiRequest;
use App\Models\TrnKonteksOrganisasi;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonteksOrganisasiController extends Controller
{
    public function __construct(protected WorkflowService $workflow)
    {
    }

    public function index()
    {
        $daftar = TrnKonteksOrganisasi::where('upt_id', Auth::user()->upt_id)
            ->with('createdBy')
            ->latest()
            ->paginate(15);

        return view('konteks.index', compact('daftar'));
    }

    public function create()
    {
        return view('konteks.create');
    }

    public function store(StoreKonteksOrganisasiRequest $request)
    {
        $data = $request->validated();
        $data['upt_id'] = Auth::user()->upt_id;
        $data['created_by'] = Auth::id();

        $konteks = TrnKonteksOrganisasi::create($data);

        return redirect()->route('konteks.show', $konteks)
            ->with('status', 'Konteks Organisasi berhasil disimpan sebagai draft.');
    }

    public function show(TrnKonteksOrganisasi $konteks)
    {
        $this->authorize('view', $konteks);

        return view('konteks.show', compact('konteks'));
    }

    public function edit(TrnKonteksOrganisasi $konteks)
    {
        $this->authorize('update', $konteks);

        return view('konteks.edit', compact('konteks'));
    }

    public function update(StoreKonteksOrganisasiRequest $request, TrnKonteksOrganisasi $konteks)
    {
        $this->authorize('update', $konteks);

        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        $konteks->update($data);

        return redirect()->route('konteks.show', $konteks)->with('status', 'Perubahan tersimpan.');
    }

    public function destroy(TrnKonteksOrganisasi $konteks)
    {
        $this->authorize('delete', $konteks);
        $konteks->delete();

        return redirect()->route('konteks.index')->with('status', 'Konteks Organisasi dihapus.');
    }

    /** Lini 1 -> Lini 2 */
    public function ajukanReviu(TrnKonteksOrganisasi $konteks)
    {
        $this->authorize('ajukanReviu', $konteks);
        $this->workflow->ajukanReviuLini2($konteks);

        return back()->with('status', 'Diajukan untuk reviu Lini 2 (Ketua Tim SPI / Auditor Internal).');
    }

    /** Lini 2 mereviu */
    public function reviuLini2(Request $request, TrnKonteksOrganisasi $konteks)
    {
        $this->authorize('reviewLini2', $konteks);

        $request->validate(['disetujui' => ['required', 'boolean'], 'catatan' => ['nullable', 'string']]);

        $this->workflow->reviuLini2(
            $konteks,
            Auth::user(),
            $request->boolean('disetujui'),
            $request->input('catatan')
        );

        return back()->with('status', $request->boolean('disetujui')
            ? 'Konteks Organisasi disetujui pada reviu Lini 2.'
            : 'Konteks Organisasi ditolak pada reviu Lini 2.');
    }

    /** Lini 1 - Kepala UPR: sign-off final */
    public function approve(Request $request, TrnKonteksOrganisasi $konteks)
    {
        $this->authorize('approveFinal', $konteks);

        $request->validate(['signature_key' => ['required', 'string']]);

        $this->workflow->approveKepalaUpt($konteks, Auth::user(), $request->input('signature_key'));

        return back()->with('status', 'Konteks Organisasi disetujui & ditandatangani secara digital.');
    }

    public function reject(Request $request, TrnKonteksOrganisasi $konteks)
    {
        $this->authorize('approveFinal', $konteks);

        $request->validate(['catatan' => ['required', 'string']]);

        $this->workflow->rejectKepalaUpt($konteks, $request->input('catatan'));

        return back()->with('status', 'Konteks Organisasi ditolak oleh Kepala UPR.');
    }
}

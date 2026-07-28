<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIdentifikasiRisikoRequest;
use App\Models\RefKategoriRisiko;
use App\Models\SysUser;
use App\Models\TrnIdentifikasiRisiko;
use App\Models\TrnKonteksOrganisasi;
use App\Services\PernyataanRisikoValidationService;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IdentifikasiRisikoController extends Controller
{
    public function __construct(
        protected WorkflowService $workflow,
        protected PernyataanRisikoValidationService $validasiPernyataan
    ) {
    }

    public function index()
    {
        $daftar = TrnIdentifikasiRisiko::where('upt_id', Auth::user()->upt_id)
            ->with(['kategoriRisiko', 'analisisTerakhir'])
            ->latest()
            ->paginate(15);

        return view('identifikasi.index', compact('daftar'));
    }

    public function create()
    {
        $kontekses = TrnKonteksOrganisasi::where('upt_id', Auth::user()->upt_id)
            ->where('status', TrnKonteksOrganisasi::STATUS_DISETUJUI_KEPALA_UPT)
            ->get();
        $kategoris = RefKategoriRisiko::orderBy('prioritas')->get();
        $users = SysUser::where('upt_id', Auth::user()->upt_id)->orderBy('nama')->get();

        return view('identifikasi.create', compact('kontekses', 'kategoris', 'users'));
    }

    public function store(StoreIdentifikasiRisikoRequest $request)
    {
        $data = $request->validated();
        $uptId = Auth::user()->upt_id;

        // Cegah duplikasi kode_risiko per UPT+tahun saat input bersamaan (Catatan Implementasi #2, Revisi 2)
        $risiko = DB::transaction(function () use ($data, $uptId) {
            $duplikat = TrnIdentifikasiRisiko::where('upt_id', $uptId)
                ->where('tahun_anggaran', $data['tahun_anggaran'])
                ->where('kode_risiko', $data['kode_risiko'])
                ->lockForUpdate()
                ->exists();

            if ($duplikat) {
                throw ValidationException::withMessages([
                    'kode_risiko' => "Kode risiko '{$data['kode_risiko']}' sudah dipakai UPT ini pada tahun anggaran {$data['tahun_anggaran']}.",
                ]);
            }

            $data['upt_id'] = $uptId;
            $data['created_by'] = Auth::id();

            return TrnIdentifikasiRisiko::create($data);
        });

        return redirect()->route('identifikasi.show', $risiko)
            ->with('status', 'Identifikasi Risiko berhasil disimpan sebagai draft.');
    }

    public function show(TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('view', $risiko);
        $risiko->load(['kategoriRisiko', 'pemilikRisiko', 'konteks', 'analisisRisikos.areaDampak', 'analisisRisikos.rtps']);

        return view('identifikasi.show', compact('risiko'));
    }

    public function edit(TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('update', $risiko);
        $kategoris = RefKategoriRisiko::orderBy('prioritas')->get();
        $users = SysUser::where('upt_id', Auth::user()->upt_id)->orderBy('nama')->get();

        return view('identifikasi.edit', compact('risiko', 'kategoris', 'users'));
    }

    public function update(StoreIdentifikasiRisikoRequest $request, TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('update', $risiko);

        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        $risiko->update($data);

        return redirect()->route('identifikasi.show', $risiko)->with('status', 'Perubahan tersimpan.');
    }

    public function destroy(TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('delete', $risiko);
        $risiko->delete();

        return redirect()->route('identifikasi.index')->with('status', 'Identifikasi Risiko dihapus.');
    }

    public function ajukanReviu(TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('ajukanReviu', $risiko);
        $this->workflow->ajukanReviuLini2($risiko);

        return back()->with('status', 'Diajukan untuk reviu Lini 2.');
    }

    public function reviuLini2(Request $request, TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('reviewLini2', $risiko);
        $request->validate(['disetujui' => ['required', 'boolean'], 'catatan' => ['nullable', 'string']]);

        $this->workflow->reviuLini2(
            $risiko,
            Auth::user(),
            $request->boolean('disetujui'),
            $request->input('catatan')
        );

        return back()->with('status', $request->boolean('disetujui')
            ? 'Identifikasi Risiko disetujui pada reviu Lini 2.'
            : 'Identifikasi Risiko ditolak pada reviu Lini 2.');
    }

    public function approve(Request $request, TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('approveFinal', $risiko);
        $request->validate(['signature_key' => ['required', 'string']]);

        $this->workflow->approveKepalaUpt($risiko, Auth::user(), $request->input('signature_key'));

        return back()->with('status', 'Identifikasi Risiko disetujui & ditandatangani secara digital.');
    }

    public function reject(Request $request, TrnIdentifikasiRisiko $risiko)
    {
        $this->authorize('approveFinal', $risiko);
        $request->validate(['catatan' => ['required', 'string']]);

        $this->workflow->rejectKepalaUpt($risiko, $request->input('catatan'));

        return back()->with('status', 'Identifikasi Risiko ditolak oleh Kepala UPR.');
    }

    /** Dipanggil AJAX dari form (on blur) utk validasi kaidah pernyataan risiko K01/K03/K07 */
    public function previewValidasiPernyataan(Request $request)
    {
        $request->validate([
            'pernyataan_risiko' => ['required', 'string'],
            'sasaran_strategis' => ['nullable', 'string'],
        ]);

        $pelanggaran = $this->validasiPernyataan->validasiTeks(
            $request->input('pernyataan_risiko'),
            $request->input('sasaran_strategis')
        );

        return response()->json(['pelanggaran' => $pelanggaran]);
    }
}

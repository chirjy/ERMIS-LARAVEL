<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemantauanRequest;
use App\Models\TrnPemantauanReviu;
use App\Models\TrnRencanaTindakPengendalian;
use Illuminate\Support\Facades\Auth;

class PemantauanController extends Controller
{
    public function create(TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('catatPemantauan', $rtp);

        return view('pemantauan.create', compact('rtp'));
    }

    public function store(StorePemantauanRequest $request, TrnRencanaTindakPengendalian $rtp)
    {
        $this->authorize('catatPemantauan', $rtp);

        $data = $request->validated();
        $data['rtp_id'] = $rtp->id;
        $data['dilaporkan_oleh'] = Auth::id();

        TrnPemantauanReviu::create($data);

        return redirect()->route('rtp.show', $rtp)
            ->with('status', 'Progress pemantauan berhasil dicatat.');
    }
}

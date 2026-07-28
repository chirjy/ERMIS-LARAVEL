@php
    $warna = match($status) {
        'DRAFT' => 'bg-slate-100 text-slate-700',
        'DIAJUKAN_REVIU_LINI2' => 'bg-blue-100 text-blue-700',
        'DITOLAK_LINI2', 'DITOLAK_KEPALA_UPT' => 'bg-red-100 text-red-700',
        'DIREVIU_LINI2' => 'bg-amber-100 text-amber-700',
        'DISETUJUI_KEPALA_UPT' => 'bg-teal-100 text-teal-800',
        'DIESKALASI_LINI3' => 'bg-purple-100 text-purple-700',
        default => 'bg-slate-100 text-slate-700',
    };
    $label = match($status) {
        'DRAFT' => 'Draft',
        'DIAJUKAN_REVIU_LINI2' => 'Diajukan Reviu Lini 2',
        'DITOLAK_LINI2' => 'Ditolak Lini 2',
        'DIREVIU_LINI2' => 'Direviu Lini 2',
        'DITOLAK_KEPALA_UPT' => 'Ditolak Kepala UPT',
        'DISETUJUI_KEPALA_UPT' => 'Disetujui Kepala UPT',
        'DIESKALASI_LINI3' => 'Dieskalasi ke Lini 3',
        default => $status,
    };
@endphp
<span class="badge {{ $warna }}">{{ $label }}</span>

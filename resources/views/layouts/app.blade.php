<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ERMIS BPOM')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

@auth
<div class="min-h-screen flex">
    <aside class="w-64 bg-ermis-navy text-slate-200 flex-shrink-0 hidden md:flex md:flex-col">
        <div class="px-5 py-5 border-b border-white/10">
            <div class="text-white font-bold text-lg leading-tight">ERMIS BPOM</div>
            <div class="text-xs text-ermis-teal font-medium">Manajemen Risiko Terpadu</div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white font-semibold' : '' }}">
                Dashboard
            </a>
            <div class="pt-3 pb-1 px-3 text-[11px] uppercase tracking-wider text-slate-400">Siklus Manajemen Risiko</div>
            <a href="{{ route('konteks.index') }}" class="block px-3 py-2 rounded-md hover:bg-white/10 {{ request()->routeIs('konteks.*') ? 'bg-white/10 text-white font-semibold' : '' }}">
                Konteks Organisasi
            </a>
            <a href="{{ route('identifikasi.index') }}" class="block px-3 py-2 rounded-md hover:bg-white/10 {{ request()->routeIs('identifikasi.*') ? 'bg-white/10 text-white font-semibold' : '' }}">
                Identifikasi &amp; Analisis Risiko
            </a>
            <div class="pt-3 pb-1 px-3 text-[11px] uppercase tracking-wider text-slate-400">Knowledge Base</div>
            <a href="{{ route('knowledge-base.glosarium') }}" class="block px-3 py-2 rounded-md hover:bg-white/10 {{ request()->routeIs('knowledge-base.glosarium') ? 'bg-white/10 text-white font-semibold' : '' }}">
                Glosarium
            </a>
            <a href="{{ route('knowledge-base.kriteria') }}" class="block px-3 py-2 rounded-md hover:bg-white/10 {{ request()->routeIs('knowledge-base.kriteria') ? 'bg-white/10 text-white font-semibold' : '' }}">
                Kriteria Kemungkinan &amp; Dampak
            </a>
            <a href="{{ route('knowledge-base.kaidah') }}" class="block px-3 py-2 rounded-md hover:bg-white/10 {{ request()->routeIs('knowledge-base.kaidah') ? 'bg-white/10 text-white font-semibold' : '' }}">
                Kaidah Pernyataan Risiko
            </a>
        </nav>
        <div class="px-5 py-4 border-t border-white/10 text-xs text-slate-400">
            {{ auth()->user()->upt?->nama }}
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-slate-200 px-4 md:px-6 py-3 flex items-center justify-between">
            <h1 class="text-base md:text-lg font-semibold text-slate-900">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-600 hidden sm:inline">{{ auth()->user()->nama }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary text-xs">Keluar</button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-6">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-teal-50 border border-teal-200 text-teal-800 text-sm px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@else
    @yield('content')
@endauth

</body>
</html>

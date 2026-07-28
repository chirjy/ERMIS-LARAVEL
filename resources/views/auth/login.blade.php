@extends('layouts.app')

@section('title', 'Masuk - ERMIS BPOM')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-ermis-navy px-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-6">
            <div class="text-white font-bold text-2xl">ERMIS BPOM</div>
            <div class="text-ermis-teal text-sm font-medium">Enterprise Risk Management Information System</div>
        </div>

        <div class="card p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Masuk ke Akun Anda</h2>

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="form-input">
                </div>
                <div>
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="password" required class="form-input">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-ermis-teal">
                    Ingat saya
                </label>
                <button type="submit" class="btn-primary w-full">Masuk</button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-4">
            Akun demo (seeder): pengelola@lokapom-kotbar.test / password
        </p>
    </div>
</div>
@endsection

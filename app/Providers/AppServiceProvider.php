<?php

namespace App\Providers;

use App\Models\TrnIdentifikasiRisiko;
use App\Models\TrnKonteksOrganisasi;
use App\Models\TrnRencanaTindakPengendalian;
use App\Policies\IdentifikasiRisikoPolicy;
use App\Policies\KonteksOrganisasiPolicy;
use App\Policies\RtpPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(TrnKonteksOrganisasi::class, KonteksOrganisasiPolicy::class);
        Gate::policy(TrnIdentifikasiRisiko::class, IdentifikasiRisikoPolicy::class);
        Gate::policy(TrnRencanaTindakPengendalian::class, RtpPolicy::class);
    }
}

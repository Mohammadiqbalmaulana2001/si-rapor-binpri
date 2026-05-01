<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\WargaBinaanRepositoryInterface;
use App\Repositories\WargaBinaanRepository;
use App\Contracts\KegiatanRepositoryInterface;
use App\Repositories\KegiatanRepository;
use App\Contracts\AbsensiRepositoryInterface;
use App\Repositories\AbsensiRepository;
use App\Contracts\RaportRepositoryInterface;
use App\Repositories\RaportRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WargaBinaanRepositoryInterface::class, WargaBinaanRepository::class);
        $this->app->bind(KegiatanRepositoryInterface::class, KegiatanRepository::class);
        $this->app->bind(AbsensiRepositoryInterface::class, AbsensiRepository::class);
        $this->app->bind(RaportRepositoryInterface::class, RaportRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

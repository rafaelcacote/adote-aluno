<?php

namespace App\Providers;

use App\Models\Aluno;
use App\Observers\AlunoObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();

        Aluno::observe(AlunoObserver::class);
    }
}

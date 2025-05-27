<?php

namespace App\Providers;

use App\Models\Type;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'types' => fn() => Type::withCount(['questions' => function ($query) {
                $gradePortal = Session::get('gradePortal');
                if ($gradePortal) {
                    $query->where('grade', $gradePortal);
                }
            }])->get(),
        ]);
    }
}

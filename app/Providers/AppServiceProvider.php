<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\NotifikasiUser;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Paginator::useBootstrapFive();
        View::composer('*', function ($view) {
            $notifCount = 0;
            if (auth()->check()) {
                $notifCount = NotifikasiUser::where('id_user', auth()->user()->id_user)
                    ->where('is_read', false) // atur sesuai kolom unread kamu
                    ->count();
            }
            $view->with('notifCount', $notifCount);
        });
    }
}

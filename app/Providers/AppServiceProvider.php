<?php

namespace App\Providers;

use App\Models\Tablas\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer("theme.bsb.menu", function($view){
            $menus = DB::table('TBL_Menu as m')
                ->join('TBL_Menu_Usuario as mu', 'mu.MN_USR_Menu_Id', '=', 'm.id')
                ->join('TBL_Usuarios as u', 'u.id', '=', 'mu.MN_USR_Usuario_Id')
                ->where('u.id', '=', session()->get('Usuario_Id'))
                ->select('m.*')
                ->orderBy('m.MN_Orden_Menu')
                ->get()
                ->toArray();
            $view->with('menusComposer', $menus);
        });
    }
}

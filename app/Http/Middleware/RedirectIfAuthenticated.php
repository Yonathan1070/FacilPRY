<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $rol = session()->all();
            switch ($rol['Rol_Id']) {
                case '1':
                    return redirect('/administrador');;
                    break;
                
                case '2':
                    return redirect('/director');
                    break;
    
                case '3':
                    return redirect('/cliente');
                    break;
    
                default:
                    switch ($rol['Sub_Rol_Id']) {
                        case '4':
                            return redirect('/perfil-operacion');
                            break;
                    }
            }
        }

        return $next($request);
    }
}

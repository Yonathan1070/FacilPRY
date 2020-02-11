<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermisoDirector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->permiso())
            return $next($request);
        
        
        return back()->with('mensaje', 'No tiene permiso para entrar aquí!');
    }

    private function permiso(){
        $rol = Auth::user()->roles()->get();
        return $rol[0]['RLS_Rol_Id'] == '2';
    }
}

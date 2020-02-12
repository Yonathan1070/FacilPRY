<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PermisoAdministrador
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
        if(session()->get('Sub_Rol_Id') == null){
            $rol = Auth::user()->roles()->get();
            return $rol[0]['RLS_Rol_Id'] == '1';
        }else{
            return session()->get('Sub_Rol_Id') == '1';
        }
    }
}

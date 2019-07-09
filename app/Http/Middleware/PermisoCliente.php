<?php

namespace App\Http\Middleware;

use Closure;

class PermisoCliente
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
        return session()->get('Rol_Nombre') == 'Cliente';
    }
}

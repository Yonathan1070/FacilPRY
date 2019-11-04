<?php

use App\Models\Tablas\Permiso;
use Illuminate\Support\Facades\DB;

if (!function_exists('canUser')) {
    function can($permiso, $redirect = true)
    {
        if (session()->get('Rol_Nombre') == 'Administrador') {
            return true;
        } else {
            $usuariosId = session()->get('Usuario_Id');
            $permisos = DB::table('TBL_Permiso as p')
                ->join('TBL_Permiso_Usuario as pu', 'pu.PRM_USR_Permiso_Id', '=', 'p.id')
                ->join('TBL_Usuarios as u', 'u.id', '=', 'pu.PRM_USR_Usuario_Id')
                ->where('PRM_USR_Usuario_Id', $usuariosId)
                ->where('PRM_Slug_Permiso', $permiso)
                ->select('p.PRM_Slug_Permiso')
                ->first();
            if ($permisos == null) {
                if ($redirect) {
                    if (!request()->ajax())
                        return redirect()->back()->with('mensaje', 'No tiene permisos para entrar en este modulo')->send();
                    return false;
                } else {
                    return false;
                }
            }
            return true;
        }
    }

    function can2($permiso, $redirect = true)
    {
        if (session()->get('Rol_Nombre') == 'Administrador') {
            return true;
        } else {
            $usuariosId = session()->get('Usuario_Id');
            $permisos = DB::table('TBL_Permiso as p')
                ->join('TBL_Permiso_Usuario as pu', 'pu.PRM_USR_Permiso_Id', '=', 'p.id')
                ->join('TBL_Usuarios as u', 'u.id', '=', 'pu.PRM_USR_Usuario_Id')
                ->where('PRM_USR_Usuario_Id', $usuariosId)
                ->where('PRM_Slug_Permiso', $permiso)
                ->select('p.PRM_Slug_Permiso')
                ->first();
            if ($permisos == null) {
                if ($redirect) {
                    if (!request()->ajax())
                        return false;
                    return false;
                } else {
                    return false;
                }
            }
            return true;
        }
    }
}

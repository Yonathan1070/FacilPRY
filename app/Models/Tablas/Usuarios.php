<?php

namespace App\Models\Tablas;

use App\Http\Requests\ValidacionUsuario;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class Usuarios extends Authenticatable
{
    protected $remember_token = false;
    protected $table = 'TBL_Usuarios';
    protected $fillable = ['USR_Tipo_Documento_Usuario',
        'USR_Documento_Usuario',
        'USR_Nombres_Usuario',
        'USR_Apellidos_Usuario',
        'USR_Fecha_Nacimiento_Usuario',
        'USR_Direccion_Residencia_Usuario',
        'USR_Telefono_Usuario',
        'USR_Correo_Usuario',
        'USR_Nombre_Usuario',
        'password',
        'USR_Foto_Perfil_Usuario',
        'USR_Supervisor_Id',
        'USR_Empresa_Id',
        'USR_Costo_Hora'];
    protected $guarded = ['id'];
    
    public function roles(){
        return $this->belongsToMany(Roles::class, 'TBL_Usuarios_Roles', 'USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id')->withPivot('USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id');
    }

    public function setSession($roles){
        if (count($roles) == 1) {
            Session::put([
                'Sub_Rol_Id' => $roles[0]['RLS_Rol_Id'],
                'Rol_Id' => $roles[0]['id'],
                'Rol_Nombre' => $roles[0]['RLS_Nombre_Rol'],
                'Usuario_Id' => $this->id,
                'Empresa_Id' => $this->USR_Empresa_Id
            ]);
        }
    }

    public static function crearUsuario($request){
        if($request['USR_Costo_Hora'] == null){
            $request['USR_Costo_Hora'] = 0;
        }
        if($request->id == null){
            $request->id = session()->get('Empresa_Id');
        }
        Usuarios::create([
            'USR_Tipo_Documento_Usuario' => $request['USR_Tipo_Documento_Usuario'],
            'USR_Documento_Usuario' => $request['USR_Documento_Usuario'],
            'USR_Nombres_Usuario' => $request['USR_Nombres_Usuario'],
            'USR_Apellidos_Usuario' => $request['USR_Apellidos_Usuario'],
            'USR_Fecha_Nacimiento_Usuario' => $request['USR_Fecha_Nacimiento_Usuario'],
            'USR_Direccion_Residencia_Usuario' => $request['USR_Direccion_Residencia_Usuario'],
            'USR_Telefono_Usuario' => $request['USR_Telefono_Usuario'],
            'USR_Correo_Usuario' => $request['USR_Correo_Usuario'],
            'USR_Nombre_Usuario' => $request['USR_Nombre_Usuario'],
            'password' => bcrypt($request['USR_Nombre_Usuario']),
            'USR_Supervisor_Id' => session()->get('Usuario_Id'),
            'USR_Empresa_Id' => $request->id,
            'USR_Costo_Hora' => $request['USR_Costo_Hora']
        ]);
    }

    public static function obtenerUsuario($documento){
        return Usuarios::where('USR_Documento_Usuario', '=', $documento)->first();
    }

    public static function editarUsuario($request, $id){
        Usuarios::findOrFail($id)->update([
            'USR_Documento_Usuario' => $request['USR_Documento_Usuario'],
            'USR_Nombres_Usuario' => $request['USR_Nombres_Usuario'],
            'USR_Apellidos_Usuario' => $request['USR_Apellidos_Usuario'],
            'USR_Fecha_Nacimiento_Usuario' => $request['USR_Fecha_Nacimiento_Usuario'],
            'USR_Direccion_Residencia_Usuario' => $request['USR_Direccion_Residencia_Usuario'],
            'USR_Telefono_Usuario' => $request['USR_Telefono_Usuario'],
            'USR_Correo_Usuario' => $request['USR_Correo_Usuario'],
            'USR_Nombre_Usuario' => $request['USR_Nombre_Usuario'],
            'USR_Costo_Hora' => $request['USR_Costo_Hora']
        ]);
    }

    public static function enviarcorreo($request, $mensaje, $asunto, $plantilla){
        Mail::send($plantilla, [
            'nombre' => $request['USR_Nombres_Usuario'].' '.$request['USR_Apellidos_Usuario'],
            'username' => $request['USR_Nombre_Usuario']], function($message) use ($request, $mensaje, $asunto){
            $message->from('from@example.com', 'Example');
            $message->to($request['USR_Correo_Usuario'], $mensaje)
                ->subject($asunto);
        });
    }
}

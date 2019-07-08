<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionUsuario extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'USR_Tipo_Documento' => 'required|max:30',
            'USR_Documento' => 'required|max:50|unique:TBL_Roles,RLS_Nombre,' . $this->route('USR_Documento'),
            'USR_Nombre' => 'required|max:50',
            'USR_Apellido' => 'required|max:50',
            'USR_Fecha_Nacimiento' => 'required',
            'USR_Direccion_Residencia' => 'required|max:100',
            'USR_Telefono' => 'required|max:20',
            'USR_Correo' => 'required|max:100',
            'USR_Nombre_Usuario' => 'required|max:15',
            'USR_Clave_Usuario' => 'required|max:15',
        ];
    }
}

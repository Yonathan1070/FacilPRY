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
            'USR_Documento' => 'required|max:50|unique:TBL_Usuarios,USR_Documento,' . $this->route('id'),
            'USR_Nombre' => 'required|max:50',
            'USR_Apellido' => 'required|max:50',
            'USR_Fecha_Nacimiento' => 'required|date|before:tomorrow',
            'USR_Direccion_Residencia' => 'required|max:100',
            'USR_Telefono' => 'required|max:20',
            'USR_Correo' => 'required|max:100|unique:TBL_Usuarios,USR_Correo,' . $this->route('id'),
            'USR_Nombre_Usuario' => 'required|max:15|unique:TBL_Usuarios,USR_Nombre_Usuario,' . $this->route('id'),
        ];
    }
}

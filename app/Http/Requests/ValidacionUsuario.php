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

    public function messages()
    {
        return [
            'USR_Documento.required' => 'El Documento de identidad es requerido.',
            'USR_Documento.max' => 'No puede exceder el limite de :max carácteres.',
            'USR_Documento.unique' => 'El documento ya se encuentra en uso.',
            'USR_Nombre.required' => 'El Nombre del Usuario es requerido.',
            'USR_Nombre.max' => 'No puede exceder el limite de :max caracteres.',
            'USR_Apellido.required' => 'El Nombre del Usuario es requerido.',
            'USR_Apellido.max' => 'No puede exceder el limite de :max caracteres.',
            'USR_Fecha_Nacimiento.required' => 'La fecha de nacimiento es requerida.',
            'USR_Fecha_Nacimiento.date' => 'Seleccione una fecha válida.',
            'USR_Fecha_Nacimiento.before' => 'Seleccione una fecha válida.',
            'USR_Direccion_Residencia.required'  => 'La Dirección de Residencia es requerida.',
            'USR_Direccion_Residencia.max'  => 'No puede exceder el limite de :max carácteres.',
            'USR_Telefono.required'  => 'El Número de telefono es requerido.',
            'USR_Telefono.max'  => 'No puede exceder el limite de :max carácteres.',
            'USR_Correo.required' => 'El Correo electrónico es requerido.',
            'USR_Correo.max' => 'No puede exceder el limite de :max carácteres.',
            'USR_Correo.unique' => 'El correo electrónco ya se encuentra en uso.',
            'USR_Nombre_Usuario.required' => 'El Nombre de usuario es requerido.',
            'USR_Nombre_Usuario.max' => 'No puede exceder el limite de :max carácteres.',
            'USR_Nombre_Usuario.unique' => 'El nombre de usuario ya se encuentra en uso.',
        ];
    }
}

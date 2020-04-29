<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ValidacionCliente extends FormRequest
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
            'USR_Documento_Usuario' => 'required|numeric|max:50|unique:TBL_Usuarios,USR_Documento_Usuario,' . $this->route('idC'),
            'USR_Nombres_Usuario' => 'required|max:50|regex:/^[a-zA-Z]+$/u',
            'USR_Apellidos_Usuario' => 'required|max:50|regex:/^[a-zA-Z]+$/u',
            'USR_Fecha_Nacimiento_Usuario' => 'required|date|before:'.Carbon::now()->subYear(18).'|after_or_equal:'.Carbon::now()->subYear(100),
            'USR_Direccion_Residencia_Usuario' => 'required|max:100',
            'USR_Telefono_Usuario' => 'required|max:20|numeric',
            'USR_Correo_Usuario' => 'required|max:100|email|unique:TBL_Usuarios,USR_Correo_Usuario,' . $this->route('idC'),
            'USR_Nombre_Usuario' => 'required|max:15|unique:TBL_Usuarios,USR_Nombre_Usuario,' . $this->route('idC'),
        ];
    }

    public function messages()
    {
        return [
            'USR_Documento_Usuario.required' => 'El Documento de identidad es requerido.',
            'USR_Documento_Usuario.max' => 'No puede exceder el limite de :max carácteres.',
            'USR_Documento_Usuario.unique' => 'El documento ya se encuentra en uso.',
            'USR_Documento_Usuario.numeric' => 'El documento no debe contener letras ni caracteres especiales.',
            'USR_Nombres_Usuario.required' => 'El Nombre del Usuario es requerido.',
            'USR_Nombres_Usuario.max' => 'No puede exceder el limite de :max caracteres.',
            'USR_Nombres_Usuario.regex' => 'Los nombres del usuario no deben contener números',
            'USR_Apellidos_Usuario.required' => 'El Nombre del Usuario es requerido.',
            'USR_Apellidos_Usuario.max' => 'No puede exceder el limite de :max caracteres.',
            'USR_Apellidos_Usuario.regex' => 'Los apellidos del usuario no deben contener números.',
            'USR_Fecha_Nacimiento_Usuario.required' => 'La fecha de nacimiento es requerida.',
            'USR_Fecha_Nacimiento_Usuario.date' => 'Seleccione una fecha válida.',
            'USR_Fecha_Nacimiento_Usuario.before' => 'El usuario debe ser mayor de edad',
            'USR_Fecha_Nacimiento_Usuario.after_or_equal' => 'El usuario no debe exceder los 100 años',
            'USR_Direccion_Residencia_Usuario.required'  => 'La Dirección de Residencia es requerida.',
            'USR_Direccion_Residencia_Usuario.max'  => 'No puede exceder el limite de :max carácteres.',
            'USR_Telefono_Usuario.required'  => 'El Número de telefono es requerido.',
            'USR_Telefono_Usuario.max'  => 'No puede exceder el limite de :max carácteres.',
            'USR_Telefono_Usuario.numeric' => 'El número de telefono no debe contener letras ni caracteres especiales.',
            'USR_Correo_Usuario.required' => 'El Correo electrónico es requerido.',
            'USR_Correo_Usuario.max' => 'No puede exceder el limite de :max carácteres.',
            'USR_Correo_Usuario.unique' => 'El correo electrónco ya se encuentra en uso.',
            'USR_Correo_Usuario.email' => 'Digite un correo electrónco válido.',
            'USR_Nombre_Usuario.required' => 'El Nombre de usuario es requerido.',
            'USR_Nombre_Usuario.max' => 'No puede exceder el limite de :max carácteres.',
            'USR_Nombre_Usuario.unique' => 'El nombre de usuario ya se encuentra en uso.',
        ];
    }
}

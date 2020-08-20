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
    public function rules($id)
    {
        return [
            'USR_Documento_Usuario' => 'required|max:50|regex:/^[0-9]+$/u|unique:TBL_Usuarios,USR_Documento_Usuario,' . $id,
            'USR_Nombres_Usuario' => 'required|max:50|regex:/^[a-zA-Z ]+$/u',
            'USR_Apellidos_Usuario' => 'required|max:50|regex:/^[a-zA-Z ]+$/u',
            'USR_Direccion_Residencia_Usuario' => 'max:100',
            'USR_Telefono_Usuario' => 'required|max:20|regex:/^[0-9]+$/u',
            'USR_Correo_Usuario' => 'required|max:100|email|unique:TBL_Usuarios,USR_Correo_Usuario,' . $id,
            'USR_Nombre_Usuario' => 'required|max:15|unique:TBL_Usuarios,USR_Nombre_Usuario,' . $id,
        ];
    }

    public function messages()
    {
        return [
            'USR_Documento_Usuario.required' => 'El documento de identidad es requerido.',
            'USR_Documento_Usuario.max' => 'El documento no puede exceder el limite de :max carácteres.',
            'USR_Documento_Usuario.unique' => 'El documento ya se encuentra en uso.',
            'USR_Documento_Usuario.regex' => 'El documento no debe contener letras ni caracteres especiales.',
            'USR_Nombres_Usuario.required' => 'Los nombres del usuario es requerido.',
            'USR_Nombres_Usuario.max' => 'El nombre del usuario no puede exceder el limite de :max caracteres.',
            'USR_Nombres_Usuario.regex' => 'El nombre del usuario no debe contener números ni caracteres especiales',
            'USR_Apellidos_Usuario.required' => 'El apellido del usuario es requerido.',
            'USR_Apellidos_Usuario.max' => 'El apellido del usuario no puede exceder el limite de :max caracteres.',
            'USR_Apellidos_Usuario.regex' => 'El apellido del usuario no debe contener números ni caracteres especiales.',
            'USR_Direccion_Residencia_Usuario.max'  => 'La dirección no puede exceder el limite de :max carácteres.',
            'USR_Telefono_Usuario.required'  => 'El número de teléfono es requerido.',
            'USR_Telefono_Usuario.max'  => 'El número de teléfono no puede exceder el limite de :max carácteres.',
            'USR_Telefono_Usuario.regex' => 'El número de telefono no debe contener letras ni caracteres especiales.',
            'USR_Correo_Usuario.required' => 'El Correo electrónico es requerido.',
            'USR_Correo_Usuario.max' => 'El correo electrónico no puede exceder el limite de :max carácteres.',
            'USR_Correo_Usuario.unique' => 'El correo electrónco ya se encuentra en uso.',
            'USR_Correo_Usuario.email' => 'Digite un correo electrónco válido.',
            'USR_Nombre_Usuario.required' => 'El nombre de usuario es requerido.',
            'USR_Nombre_Usuario.max' => 'El nombre de usuario no puede exceder el limite de :max carácteres.',
            'USR_Nombre_Usuario.unique' => 'El nombre de usuario ya se encuentra en uso.',
        ];
    }
}

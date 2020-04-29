<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionEmpresa extends FormRequest
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
            'EMP_NIT_Empresa' => 'required|numeric|max:20|unique:TBL_Empresas,EMP_NIT_Empresa,' . $this->route('id'),
            'EMP_Nombre_Empresa' => 'required|max:100|regex:/^[a-zA-Z]+$/u|unique:TBL_Empresas,EMP_Nombre_Empresa,' . $this->route('id'),
            'EMP_Direccion_Empresa' => 'required|max:100',
            'EMP_Telefono_Empresa' => 'required|max:20|numeric',
            'EMP_Correo_Empresa' => 'required|max:100|email|unique:TBL_Empresas,EMP_Correo_Empresa,' . $this->route('id'),
        ];
    }

    public function messages()
    {
        return [
            'EMP_NIT_Empresa.required' => 'El NIT de la empresa es requerido.',
            'EMP_NIT_Empresa.max' => 'El NIT no puede exceder el limite de :max carácteres.',
            'EMP_NIT_Empresa.unique' => 'El NIT ya se encuentra en uso.',
            'EMP_NIT_Empresa.numeric' => 'El NIT no debe contener letras ni caracteres especiales.',
            'EMP_Nombre_Empresa.required' => 'El nombre de la empresa es requerido.',
            'EMP_Nombre_Empresa.max' => 'El nombre de la empresa no puede exceder el limite de :max caracteres.',
            'EMP_Nombre_Empresa.regex' => 'El nombre de la empresa no debe contener números',
            'EMP_Nombre_Empresa.unique' => 'El nombre de la empresa ya se encuentra registrado.',
            'EMP_Direccion_Empresa.required'  => 'La dirección de la empresa es requerida.',
            'EMP_Direccion_Empresa.max'  => 'La dirección de la empresa no puede exceder el limite de :max carácteres.',
            'EMP_Telefono_Empresa.required'  => 'El número de telefono es requerido.',
            'EMP_Telefono_Empresa.max'  => 'El número de telefono no puede exceder el limite de :max carácteres.',
            'EMP_Telefono_Empresa.numeric' => 'El número de telefono no debe contener letras ni caracteres especiales.',
            'EMP_Correo_Empresa.required' => 'El correo electrónico es requerido.',
            'EMP_Correo_Empresa.max' => 'El correo electrónico no puede exceder el limite de :max carácteres.',
            'EMP_Correo_Empresa.unique' => 'El correo electrónico ya se encuentra en uso.',
            'EMP_Correo_Empresa.email' => 'Digite un correo electrónco válido.',
        ];
    }
}

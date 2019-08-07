<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionRol extends FormRequest
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
            'RLS_Nombre' => 'required|max:30|unique:TBL_Roles,RLS_Nombre,' . $this->route('id'),
            'RLS_Descripcion' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'RLS_Nombre.required' => 'El Nombre del Rol es requerido.',
            'RLS_Nombre.unique' => 'El Nombre del Rol ya está en el sistema.',
            'RLS_Descripcion.required'  => 'La Descripción del Rol es requerida.',
        ];
    }
}

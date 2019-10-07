<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionPermiso extends FormRequest
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
            'PRM_Nombre_Permiso' => 'required|max:50|unique:TBL_Permiso,PRM_Nombre_Permiso,' . $this->route('id'),
            'PRM_Slug_Permiso' => 'required|max:100unique:TBL_Permiso,PRM_Slug_Permiso,' . $this->route('id')
        ];
    }

    public function messages()
    {
        return [
            'PRM_Nombre_Permiso.required' => 'El nombre del permiso es requerido.',
            'PRM_Nombre_Permiso.max' => 'No puede exceder el limite de :max carácteres.',
            'PRM_Nombre_Permiso.unique' => 'El nombre del permiso ya se encuentra en uso.',
            'PRM_Slug_Permiso.required' => 'El Slug del permiso es requerido.',
            'PRM_Slug_Permiso.max' => 'No puede exceder el limite de :max caracteres.',
            'PRM_Slug_Permiso.unique' => 'El Slug del permiso ya se encuentra en uso.',
        ];
    }
}

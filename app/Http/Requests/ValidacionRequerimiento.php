<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionRequerimiento extends FormRequest
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
            'REQ_Nombre_Requerimiento' => 'required|max:60',
            'REQ_Descripcion_Requerimiento' => 'required|max:500'
        ];
    }

    public function messages()
    {
        return [
            'REQ_Nombre_Requerimiento.required' => 'El nombre del requerimiento es requerido.',
            'REQ_Nombre_Requerimiento.max' => 'No puede exceder el limite de :max carácteres.',
            'REQ_Descripcion_Requerimiento.required' => 'La descripción del requerimiento es requerido.',
            'REQ_Descripcion_Requerimiento.max' => 'No puede exceder el limite de :max caracteres.',
        ];
    }
}

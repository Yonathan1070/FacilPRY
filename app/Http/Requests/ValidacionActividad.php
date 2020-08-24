<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionActividad extends FormRequest
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
            'ACT_Nombre_Actividad' => 'required|max:60',
            'ACT_Descripcion_Actividad' => 'required|max:500'
        ];
    }

    public function messages()
    {
        return [
            'ACT_Nombre_Actividad.required' => 'El nombre de la actividad es requerido.',
            'ACT_Nombre_Actividad.max' => 'No puede exceder el limite de :max carácteres.',
            'ACT_Descripcion_Actividad.required' => 'La descripción de la actividad es requerido.',
            'ACT_Descripcion_Actividad.max' => 'No puede exceder el limite de :max caracteres.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionProyecto extends FormRequest
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
            'PRY_Nombre_Proyecto' => 'required|max:100|unique:TBL_Proyectos,PRY_Nombre_Proyecto,' . $this->route('id'),
            'PRY_Descripcion_Proyecto' => 'required|max:500'
        ];
    }

    public function messages()
    {
        return [
            'PRY_Nombre_Proyecto.required' => 'El nombre del proyecto es requerido.',
            'PRY_Nombre_Proyecto.max' => 'No puede exceder el limite de :max carácteres.',
            'PRY_Nombre_Proyecto.unique' => 'El nombre del proyecto ya se encuentra en uso.',
            'PRY_Descripcion_Proyecto.required' => 'La descripción del proyecto es requerido.',
            'PRY_Descripcion_Proyecto.max' => 'No puede exceder el limite de :max caracteres.',
        ];
    }
}

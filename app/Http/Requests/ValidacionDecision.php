<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionDecision extends FormRequest
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
            'DSC_Nombre_Decision' => ['required|max:60|unique:TBL_Decisiones,DSC_Nombre_Decision,' . $this->route('id')],
            'DSC_Descripcion_Decision' => ['required|max:1000'],
            'DCS_Rango_Inicio_Decision' => ['required_with:DCS_Rango_Fin_Decision|numeric|min:1'],
            'DCS_Rango_Fin_Decision' => ['required_with:DCS_Rango_Inicio_Decision|numeric|max:100|greater_than_field:DCS_Rango_Inicio_Decision']
        ];
    }

    public function messages()
    {
        return [
            'DSC_Nombre_Decision.required' => 'El nombre de la decisión es requerido.',
            'DSC_Nombre_Decision.max' => 'No puede exceder el limite de :max caracteres.',
            'DSC_Nombre_Decision.unique' => 'El nombre de la decisión ya está siendo usada.',
            'DSC_Descripcion_Decision.required' => 'La descripción de la decisión es requerido.',
            'DSC_Descripcion_Decision.max' => 'No puede exceder el limite de :max caracteres.',
        ];
    }
}

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
            'ACT_Descripcion_Actividad' => 'required|max:500',
            'ACT_Fecha_Inicio_Actividad' => 'required|date|after:today|before:ACT_Fecha_Fin_Actividad',
            'ACT_Fecha_Fin_Actividad' => 'required|date|after:ACT_Fecha_Inicio_Actividad',
            'ACT_Costo_Actividad' => 'numeric'
        ];
    }

    public function messages()
    {
        return [
            'ACT_Nombre_Actividad.required' => 'El nombre de la actividad es requerido.',
            'ACT_Nombre_Actividad.max' => 'No puede exceder el limite de :max carácteres.',
            'ACT_Descripcion_Actividad.required' => 'La descripción de la actividad es requerido.',
            'ACT_Descripcion_Actividad.max' => 'No puede exceder el limite de :max caracteres.',
            'ACT_Fecha_Inicio_Actividad.required' => 'La fecha de inicio es requerida.',
            'ACT_Fecha_Inicio_Actividad.before' => 'la fecha de inicio no puede ser mayor que la fecha actual.',
            'ACT_Fecha_Inicio_Actividad.date' => 'Seleccione una fecha válida.',
            'ACT_Fecha_Fin_Actividad.required' => 'La fecha de finalización es requerida.',
            'ACT_Fecha_Fin_Actividad.after' => 'La fecha no puede ser menor que la fecha de inicio.',
            'ACT_Fecha_Fin_Actividad.date' => 'Seleccione una fecha válida.',
            'ACT_Costo_Actividad.required' => 'El costo del proyecto es requerido.',
        ];
    }
}

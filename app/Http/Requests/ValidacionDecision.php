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
        ];
    }
}

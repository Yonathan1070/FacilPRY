<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidacionMenu extends FormRequest
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
            'MN_Nombre_Menu' => 'required|max:50',
            'MN_Nombre_Ruta_Menu' => 'required|max:100|unique:TBL_Menu,MN_Nombre_Ruta_Menu,'.$this->route('id'),
            'MN_Icono_Menu' => 'nullable|max:50'
        ];
    }

    public function messages(){
        return[
            'MN_Nombre_Menu.required' => 'El campo Nombre es requerido.',
            'MN_Nombre_Menu.max' => 'No se puede exceder de :max caracteres.',
            'MN_Nombre_Ruta_Menu.required' => 'El campo Nombre Ruta es requerido.',
            'MN_Nombre_Ruta_Menu.max' => 'No se puede exceder de :max caracteres.',
            'MN_Nombre_Ruta_Menu.unique' => 'El nombre de la Ruta ya se encuentra registrado.',
            'MN_Icono_Menu.max' => 'No se puede exceder de :max caracteres.',
        ];
    }
}

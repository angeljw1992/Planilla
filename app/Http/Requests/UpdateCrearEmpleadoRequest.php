<?php

namespace App\Http\Requests;

use App\Models\CrearEmpleado;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCrearEmpleadoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('crear_empleado_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'codigo_empleado' => [
                'string',
                'min:5',
                'max:7',
                'required',
            ],
            'rol' => [
                'required',
            ],
            'restaurante' => [
                'required',
            ],
        ];
    }
}

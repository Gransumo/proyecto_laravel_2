<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Responses\ApiResponse;

class ProductoRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
    	$response = new ApiResponse();
        $errors = $this->getErrorsMessages();

        if (isset($errors['categoria_id'])) {
            $errors['categoria'] = $errors['categoria_id'];
            unset($errors['categoria_id']);
        }
        if (isset($errors['marca_id'])) {
            $errors['marca'] = $errors['marca_id'];
            unset($errors['marca_id']);
        }
		throw new ValidationException(
        	$validator,
        	$response->error('Errores de validación', 422, $errors)
        );
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|unique:productos,nombre,'.$this->producto->id,
            'precio' => 'required|numeric|between:0.99,999999.99',
            'cantidad_disponible' => 'required|integer|min:0|max:2147483647',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
        ];
    }

    public function messages()
{
    return [
        'nombre.required'	=> 'El campo nombre es obligatorio.',
        'nombre.unique'		=> 'El nombre ya existe en la base de datos.',
        'nombre.string'		=> 'El campo nombre debe ser una cadena de texto',

        'precio.required'	=> 'El campo precio es obligatorio.',
        'precio.numeric'	=> 'El campo precio debe ser un número.',
        'precio.between'	=> 'El campo precio debe estar entre :min y :max.',

        'cantidad_disponible.required'	=> 'El campo cantidad disponible es obligatorio.',
        'cantidad_disponible.integer'	=> 'El campo cantidad disponible debe ser un número entero.',
        'cantidad_disponible.min'		=> 'El campo cantidad disponible debe ser como mínimo :min.',
        'cantidad_disponible.max'		=> 'El campo cantidad disponible debe ser como máximo :max.',

        'categoria_id.required'	=> 'El campo categoría es obligatorio.',
        'categoria_id.exists'	=> 'La categoría seleccionada no es válida.',

        'marca_id.required'	=> 'El campo marca es obligatorio.',
        'marca_id.exists'	=> 'La marca seleccionada no es válida.',
    ];
}


    public function getErrorsMessages()
    {
        $messages = $this->validator->errors()->toArray();

        return empty($messages) ? 'Error de validación' : $messages;
    }
}

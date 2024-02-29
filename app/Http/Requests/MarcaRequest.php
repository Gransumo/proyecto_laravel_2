<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use \Illuminate\Validation\ValidationException;
use App\Http\Responses\ApiResponse;
use Illuminate\Validation\Rule;

class MarcaRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = new ApiResponse();

        throw new ValidationException(
            $validator,
            $response->error($this->getFirstErrorMessage(), 422)
        );
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rule = Rule::unique('marcas')->ignore($this->marca);
        return [
            'nombre' => ['required', 'string', 'max:255', $rule]
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.unique' => 'El nombre ya existe en la base de datos',
            'nombre.string' => 'El campo nombre debe ser una cadena de texto',
            'nombre.max' => 'El campo nombre no puede tener más de :max caracteres'
        ];
    }

    public function getFirstErrorMessage()
    {
        $messages = $this->validator->errors()->all();
        return empty($messages) ? 'Error de validación' : reset($messages);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use \Illuminate\Validation\ValidationException;
use App\Http\Responses\ApiResponse;
use App\Models\Producto;

class CompraRequest extends FormRequest
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
        return [
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|integer|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'productos.required'	=> 'La lista de productos es obligatoria y debe ser un arreglo.',
            'productos.array'		=> 'La lista de productos debe ser un arreglo.',

            'productos.*.producto_id.required'	=> 'El ID del producto es obligatorio para todos los productos.',
            'productos.*.producto_id.integer'	=> 'El ID del producto debe ser un número entero para todos los productos.',
            'productos.*.producto_id.exists'	=> 'Un producto seleccionado no es válido.',

            'productos.*.cantidad.required'		=> 'La cantidad es obligatoria para todos los productos.',
            'productos.*.cantidad.integer'		=> 'La cantidad debe ser un número entero.',
            'productos.*.cantidad.min'			=> 'La cantidad debe ser al menos :min.',
        ];
    }


    public function getFirstErrorMessage()
    {
        $messages = $this->validator->errors()->all();
        return empty($messages) ? 'Error de validación' : reset($messages);
    }

}

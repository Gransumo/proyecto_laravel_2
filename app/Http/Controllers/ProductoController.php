<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Requests\ProductoRequest;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index()
    {
        try {
            $productos = Producto::with([
                'marca' => function ($query) {
                    $query->select('id', 'nombre');
                },
                'categoria' => function ($query) {
                    $query->select('id', 'nombre');
                }
            ])->get();

            return ApiResponse::success('Lista de Productos', 200, $productos);
        } catch (Exception $e) {
            return ApiResponse::error('Error al listar los Productos: '.$e->getMessage() , 500);
        }
    }

    public function store(ProductoRequest $request)
    {
        try {
            $producto = Producto::create($request->validated());
            return ApiResponse::success('Producto creado correctamente', 201, $producto);
        } catch (Exception $e) {
            return ApiResponse::error('Error al crear el producto: '.$e->getMessage() , 500);
        }
    }


    public function show($id)
    {
        try {
            $producto = Producto::with([
                'marca' => function ($query) {
                    $query->select('id', 'nombre');
                },
                'categoria' => function ($query) {
                    $query->select('id', 'nombre');
                }
            ])->findOrFail($id);

            return ApiResponse::success('Producto obtenido correctamente', 200, $producto);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Producto no encontrado', 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error al buscar el producto: '.$e->getMessage() , 500);
        }
    }

    public function update(ProductoRequest $request, $id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->update($request->validated());
            return ApiResponse::success('Producto actualizado correctamente', 200, $producto);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Producto no encontrado', 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error al actualizar el producto: '.$e->getMessage() , 500);
        }
    }



    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();
            return ApiResponse::success('Producto eliminado correctamente', 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Producto no encontrado', 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error al eliminar el producto: '.$e->getMessage() , 500);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CategoriaController extends Controller
{
    public function index()
    {
        try {
            return ApiResponse::success('Lista de Categorias', 200, Categoria::all());
        } catch (Exception $e) {
            return ApiResponse::error('Error al listar las categorias: '.$e->getMessage() , 500);
        }
    }

    public function store(CategoriaRequest $request)
    {
        try {
            $categoria = Categoria::create($request->validated());
            return ApiResponse::success('Categoria creada con correctamente', 201, $categoria);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al crear la categoría', 500);
        }
    }

    public function show($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            return ApiResponse::success('Categoria obtenida correctamente', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Categoria no encontrada.', 404);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al obtener la categoría', 500);
        }
    }

    public function update(CategoriaRequest $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->update($request->validated());
            return ApiResponse::success('Categoría actualizada correctamente', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Categoría no encontrada', 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: '.$e->getMessage(), 422);
        }
    }


    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            return ApiResponse::success('Categoría eliminada correctamente', 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Categoría no encontrada', 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error al procesar la petición', 500);
        }
    }

    public function productosPorCategoria($id)
    {
        try {
            $categoria = Categoria::with('productos')->findOrFail($id);
            return ApiResponse::success('Categoría y lista de productos', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Categoría no encontrada', 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error al procesar la petición', 500);
        }
    }

}

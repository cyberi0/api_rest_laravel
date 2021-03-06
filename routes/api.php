<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ImageUploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/usuarios/crear', [UsuariosController::class, 'create']);
Route::put('/usuarios/actualizar', [UsuariosController::class, 'update']);
Route::get('/usuarios/todos', [UsuariosController::class, 'getAll']);
Route::get('/usuario/id/{id}', [UsuariosController::class, 'getByID'])
    ->where('id', '[0-9]+');
Route::delete('/usuario/eliminar/{id}', [UsuariosController::class, 'delete']);

Route::post('/documentos/crear', [DocumentsController::class, 'store']);
Route::delete('/documentos/eliminar/{id}', [UsuariosController::class, 'destroy']);

Route::post('/imagen/subir', [ImageUploadController::class, 'uploadImage']);

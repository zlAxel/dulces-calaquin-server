<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchaseController;

use App\Http\Controllers\Api\SearchUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = $request->user();

    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ];
});

// ! Agregamos ruta para buscar un usuario por su email
Route::post('/search-user', SearchUserController::class)->middleware('guest')->name('search-user');

// ! Agregamos la ruta para el controlador de productos
Route::apiResource('/products', ProductController::class);

// ! Agregamos la ruta para el controlador de compras
Route::apiResource('/purchases', PurchaseController::class);

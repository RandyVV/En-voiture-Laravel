<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\UserController;
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

Route::post("/utilisateur/inscription", [UserController::class, "inscription"]);
Route::post("/utilisateur/connexion", [UserController::class, "connexion"]);

Route::get("/cars", [CarsController::class, "index"]);

Route::get("/cars/{id}", [CarsController::class, "show"]);


Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::post("/cars", [CarsController::class, "store"]);
    Route::put("/cars/{id}", [CarsController::class, "update"]);
    Route::delete("/cars/{id}", [CarsController::class, "destroy"]);
});

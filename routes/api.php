<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\UtilisateurController;
use App\Http\Controllers\API\FonctionController;
use App\Http\Controllers\API\EquipeController;
use App\Http\Controllers\API\MissionController;
use App\Http\Controllers\API\PersonnelController;
use App\Http\Controllers\API\ClassementController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UtilisateurController::class, 'login']);
Route::post('register', [UtilisateurController::class, 'register']);
Route::post('logout', [UtilisateurController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'books', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [BookController::class, 'index']);
    Route::post('add', [BookController::class, 'add']);
    Route::get('edit/{id}', [BookController::class, 'edit']);
    Route::post('update/{id}', [BookController::class, 'update']);
    Route::delete('delete/{id}', [BookController::class, 'delete']);
});

Route::group(['prefix' => 'fonctions', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [FonctionController::class, 'index']);
});

Route::group(['prefix' => 'missions', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [MissionController::class, 'index']);
});


Route::group(['prefix' => 'personnels', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [PersonnelController::class, 'index']);
    Route::get('/getFirstWhere', [PersonnelController::class, 'getFirstWhere']);
    Route::get('/getMatriculeByFonction', [PersonnelController::class, 'getMatriculeByFonction']);
    Route::get('/getClassement', [PersonnelController::class, 'getClassement']);
    Route::get('/searchByFonction', [PersonnelController::class, 'searchByFonction']);
    Route::get('/getPersonnelFromCoach', [PersonnelController::class, 'getPersonnelFromCoach']);
});

Route::group(['prefix' => 'equipe', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [EquipeController::class, 'create']);
});

Route::group(['prefix' => 'classements', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [ClassementController::class, 'create']);
    Route::get('/planning', [ClassementController::class, 'getPlanning']);
});
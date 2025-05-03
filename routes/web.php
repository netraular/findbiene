<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController; // Asegúrate de importar el controlador que crearemos
use App\Http\Controllers\HomeController;  // Asegúrate de que este también esté importado

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route::get('/', function () { // Comentamos o eliminamos la ruta welcome original
//     return view('welcome');
// });

Auth::routes(['register' => false]); // <-- Modificación aquí

// La ruta /home sigue existiendo pero quizás no la uses si todo va a estar en '/'
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Nuevas rutas para la página principal y la subida de imágenes
Route::get('/', [ImageController::class, 'index'])->name('images.index');
Route::post('/upload', [ImageController::class, 'upload'])->name('images.upload')->middleware('auth'); // Protegida por autenticación
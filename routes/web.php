<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HomeController; // Keep if /home is still needed for admins?
use App\Http\Controllers\ShareController;

// ... otras rutas ...

// Remove default welcome route if not used
// Route::get('/', function () { return view('welcome'); });

// Setup authentication routes but disable registration
Auth::routes(['register' => false]);

// Home route might be irrelevant now unless used for admin panel
// If you need an admin-only area, keep this or adapt it. Requires login.
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Main image gallery and upload routes
Route::get('/', [ImageController::class, 'index'])->name('images.index');

// Upload route - NO LONGER requires authentication
Route::post('/upload', [ImageController::class, 'upload'])->name('images.upload');

// --- ADDED: Route for deleting an image ---
// Protected by 'auth' middleware. Only logged-in users can attempt.
// Controller will verify if the specific user ('netraular') is allowed.
Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy')->middleware('auth');


Route::get('/share', [ShareController::class, 'index'])->name('share');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HomeController; // Keep if /home is still needed for admins?

// Remove default welcome route if not used
// Route::get('/', function () { return view('welcome'); });

Auth::routes(['register' => false]); // Keep login for potential admin access? Or remove Auth::routes() entirely if no login needed AT ALL.

// Home route might be irrelevant now unless used for admin panel
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth'); // Still requires auth

// Main image gallery and upload routes
Route::get('/', [ImageController::class, 'index'])->name('images.index');
// --- REMOVED middleware('auth') ---
Route::post('/upload', [ImageController::class, 'upload'])->name('images.upload');
<?php

namespace App\Http\Controllers;

use App\Models\Image; // Importa el modelo Image
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Para manejar el almacenamiento

class ImageController extends Controller
{
    /**
     * Muestra la página principal con el formulario de subida (si está logueado)
     * y la galería de imágenes.
     */
    public function index()
    {
        // Obtiene las últimas 12 imágenes subidas, ordenadas por fecha de creación descendente
        $images = Image::latest()->take(12)->get();

        return view('images.index', compact('images')); // Pasamos las imágenes a la vista
    }

    /**
     * Procesa la subida de la imagen.
     */
    public function upload(Request $request)
    {
        // Validación
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096', // Max 4MB
            'description' => 'nullable|string|max:255', // Validación para la descripción opcional
        ]);

        // Guarda la imagen en storage/app/public/bienes_images
        // Asegúrate de que la carpeta exista o se creará
        $path = $request->file('image')->store('public/bienes_images');

        // Si no has ejecutado `php artisan storage:link`, hazlo ahora para
        // que las imágenes en storage/app/public sean accesibles desde public/storage
        // comando: php artisan storage:link

        // Guarda la información en la base de datos
        $image = new Image();
        $image->path = $path;
        $image->description = $request->input('description');
        $image->user_id = auth()->id(); // Asigna el ID del usuario autenticado
        $image->save();

        return back()->with('success', '¡Foto de Biene subida con éxito!'); // Redirige atrás con mensaje de éxito
    }
}
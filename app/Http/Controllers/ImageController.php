<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Importa Log
class ImageController extends Controller
{
    public function index()
    {
        $images = Image::latest()->take(18)->get(); // Show a few more? 18?
        return view('images.index', compact('images'));
    }

    public function upload(Request $request)
    {
        Log::info('Upload request received.'); // Log inicio

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        Log::info('Validation passed.');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            Log::info('File is present and valid: ' . $request->file('image')->getClientOriginalName());

            try {
                $file = $request->file('image');
                $targetDir = 'public/bienes_images'; // Directorio relativo a storage/app
                $physicalTargetDir = storage_path('app/' . $targetDir); // Ruta física

                Log::info('Attempting to store file in physical directory: ' . $physicalTargetDir);

                // Verifica si el directorio físico existe y es escribible ANTES de llamar a store()
                if (!file_exists($physicalTargetDir)) {
                     Log::info('Target directory does not exist, attempting to create.');
                     // Intenta crear el directorio recursivamente
                     if (!mkdir($physicalTargetDir, 0775, true)) {
                         Log::error('Failed to create target directory: ' . $physicalTargetDir);
                         return back()->withErrors(['upload_error' => 'Server configuration error: Could not create storage directory.']);
                     }
                     Log::info('Target directory created.');
                }

                if (!is_writable(storage_path('app/public'))) { // Verifica permiso en el directorio padre
                     Log::error('Storage parent directory is NOT writable: ' . storage_path('app/public'));
                      return back()->withErrors(['upload_error' => 'Server configuration error: Storage directory permissions issue.']);
                } else {
                     Log::info('Storage parent directory IS writable.');
                }

                if (!is_writable($physicalTargetDir)) { // Verifica permiso en el directorio específico
                     Log::error('Target directory is NOT writable: ' . $physicalTargetDir);
                     // Intenta arreglar permisos aquí como último recurso (¡CUIDADO!)
                     // chmod($physicalTargetDir, 0775);
                     // if (!is_writable($physicalTargetDir)) { // Vuelve a comprobar
                           return back()->withErrors(['upload_error' => 'Server configuration error: Cannot write to storage directory.']);
                     // }
                } else {
                    Log::info('Target directory IS writable.');
                }


                // Ahora sí, intenta guardar
                $path = $file->store('bienes_images', 'public');
                
                if ($path) {
                    Log::info('File stored successfully. Path returned by store(): ' . $path);
                    Log::info('Checking physical file exists at: ' . storage_path('app/' . $path));
                     if (file_exists(storage_path('app/' . $path))) {
                         Log::info('CONFIRMED: Physical file exists after store() call.');
                     } else {
                          Log::error('ERROR: store() returned path but physical file NOT FOUND!');
                     }

                    $image = new Image();
                    $image->path = $path;
                    $image->user_id = null;
                    $image->save();
                    Log::info('Database record saved for image ID: ' . $image->id);

                    return back()->with('success', 'Biene sighting uploaded! Thanks, adventurer!');

                } else {
                    Log::error('File storage failed! store() returned false or null.');
                    return back()->withErrors(['upload_error' => 'Could not save the image file.']);
                }

            } catch (\Exception $e) {
                Log::error('Error during file upload process: ' . $e->getMessage());
                Log::error($e->getTraceAsString()); // Log completo del error
                 return back()->withErrors(['upload_error' => 'An unexpected error occurred during upload. Please check server logs.']);
            }

        } else {
             Log::warning('Upload request failed: File not present or not valid.');
             if ($request->hasFile('image')) {
                 Log::warning('File upload error code: ' . $request->file('image')->getError());
             }
             return back()->withErrors(['image' => 'Invalid or missing image file.']); // O usa el error de validación
        }
    }
}
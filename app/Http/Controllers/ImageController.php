<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    /**
     * Define si las subidas de imágenes están actualmente abiertas o cerradas.
     * Cambia esto a 'false' cuando el evento termine.
     * @var bool
     */
    private bool $submissionsOpen = false;

    /**
     * Display a listing of the images with pagination.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch latest images, 10 per page
        $images = Image::latest()->paginate(10);
        $submissionsOpen = $this->submissionsOpen; // Pasa el estado a la vista

        return view('images.index', compact('images', 'submissionsOpen')); // <--- PASA LA VARIABLE
    }

    /**
     * Handle the incoming image upload request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        // --- PRIMERA COMPROBACIÓN: ¿Están las subidas abiertas? ---
        if (!$this->submissionsOpen) {
            Log::warning('Upload attempt rejected: Submissions are closed.');
            // Si la petición espera JSON (como nuestro script AJAX)
            if ($request->expectsJson()) {
                 return response()->json(['message' => 'Sorry, the Biene Hunt submissions are now closed.'], 403); // 403 Forbidden
            }
            // Para envíos de formulario normales (aunque usamos AJAX)
            return back()->withErrors(['upload_error' => 'Sorry, the Biene Hunt submissions are now closed.']);
        }
        // --------------------------------------------------------

        Log::info('Upload request received (Submissions are open).');

        // Validate the incoming file.
        $validated = $request->validate([
            // Ajusta max si es necesario basado en tu compresión/validación JS
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB validation from Laravel
        ]);

        Log::info('Validation passed.');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            Log::info('File is present and valid: ' . $request->file('image')->getClientOriginalName());

            try {
                $file = $request->file('image');
                $targetDirName = 'bienes_images';
                $publicDiskPath = storage_path('app/public/' . $targetDirName);

                Log::info('Attempting to store file in public disk directory: ' . $targetDirName);
                // (El resto de las comprobaciones de directorio y permisos permanecen igual)
                 if (!is_writable(storage_path('app/public'))) {
                     Log::error('Storage parent directory is NOT writable: ' . storage_path('app/public'));
                      if ($request->expectsJson()) {
                         return response()->json(['message' => 'Server configuration error: Storage directory permissions issue.'], 500);
                      }
                      return back()->withErrors(['upload_error' => 'Server configuration error: Storage directory permissions issue.']);
                 }
                 if (!file_exists($publicDiskPath)) {
                     if (!mkdir($publicDiskPath, 0775, true)) {
                         Log::error('Failed to create target directory: ' . $publicDiskPath);
                         if ($request->expectsJson()) {
                             return response()->json(['message' => 'Server configuration error: Could not create storage directory.'], 500);
                         }
                         return back()->withErrors(['upload_error' => 'Server configuration error: Could not create storage directory.']);
                     }
                 } elseif (!is_writable($publicDiskPath)) {
                     Log::error('Target directory exists but is NOT writable: ' . $publicDiskPath);
                      if ($request->expectsJson()) {
                         return response()->json(['message' => 'Server configuration error: Cannot write to storage directory.'], 500);
                      }
                     return back()->withErrors(['upload_error' => 'Server configuration error: Cannot write to storage directory.']);
                 }


                $path = $file->store($targetDirName, 'public');

                if ($path) {
                    Log::info('File stored successfully. Path returned by store(): ' . $path);

                    $image = new Image();
                    $image->path = $path;
                    $image->user_id = null;
                    $image->save();
                    Log::info('Database record saved for image ID: ' . $image->id);

                    // Para AJAX, devolvemos JSON con éxito
                    if ($request->expectsJson()) {
                         // Puedes incluir más datos si el frontend los necesita
                         return response()->json(['success' => 'Biene sighting uploaded! Thanks, adventurer!', 'image_path' => Storage::url($path)]);
                    }
                    // Para fallback (no debería ocurrir con el script actual)
                    return back()->with('success', 'Biene sighting uploaded! Thanks, adventurer!');

                } else {
                    Log::error('File storage failed! store() returned false or null.');
                     if ($request->expectsJson()) {
                         return response()->json(['message' => 'Could not save the image file.'], 500);
                     }
                    return back()->withErrors(['upload_error' => 'Could not save the image file.']);
                }

            } catch (\Exception $e) {
                Log::error('Error during file upload process: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
                 if ($request->expectsJson()) {
                     // No expongas detalles del error al cliente en producción
                     return response()->json(['message' => 'An unexpected error occurred during upload.'], 500);
                 }
                 return back()->withErrors(['upload_error' => 'An unexpected error occurred during upload. Please check server logs.']);
            }

        } else {
             Log::warning('Upload request failed: File not present or not valid.');
             if ($request->hasFile('image')) {
                 Log::warning('File upload error code: ' . $request->file('image')->getError());
             }
             $errorMessage = 'Invalid or missing image file.';
             // Si hay errores de validación previos, úsalos
             if (session('errors') && session('errors')->has('image')) {
                 $errorMessage = session('errors')->first('image');
             }
              if ($request->expectsJson()) {
                 // Devuelve el error de validación específico si existe
                 return response()->json(['message' => $errorMessage, 'errors' => ['image' => [$errorMessage]]], 422); // 422 Unprocessable Entity
              }
             return back()->withErrors(['image' => $errorMessage]);
        }
    }

    /**
     * Remove the specified image from storage and database.
     * Route Model Binding automatically finds the Image or throws 404.
     *
     * @param \App\Models\Image $image The image instance resolved by Route Model Binding.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Image $image)
    {
        // (El código de destroy no necesita cambios relacionados con la subida)
        if (!Auth::check() || Auth::user()->name !== 'netraular') {
            Log::warning('Unauthorized delete attempt for image ID: ' . $image->id . ' by user: ' . (Auth::check() ? Auth::user()->name . ' (ID: ' . Auth::id() . ')' : 'Guest'));
            abort(403, 'Unauthorized action. Only the designated user can delete images.');
        }

        Log::info('Delete request authorized for image ID: ' . $image->id . ' by user: ' . Auth::user()->name . ' (ID: ' . Auth::id() . ')');

        try {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
                Log::info('Deleted file from storage: ' . $image->path);
            } else {
                 Log::warning('File not found in storage for deletion: ' . $image->path . ' (Continuing to delete DB record)');
            }

            $image->delete();
            Log::info('Deleted database record for image ID: ' . $image->id);

            return back()->with('success', 'Biene sighting deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Error deleting image ID ' . $image->id . ': ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->withErrors(['delete_error' => 'Could not delete the image due to a server error. Please check logs.']);
        }
    }
}
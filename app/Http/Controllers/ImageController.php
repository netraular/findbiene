<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Importa Log
use Illuminate\Support\Facades\Auth; // Importa Auth

class ImageController extends Controller
{
    /**
     * Display a listing of the images.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch latest images, limit to 18
        $images = Image::latest()->take(18)->get();
        return view('images.index', compact('images'));
    }

    /**
     * Handle the incoming image upload request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        Log::info('Upload request received.');

        // Validate the incoming file.
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB validation from Laravel
        ]);

        Log::info('Validation passed.');

        // Check if file is present and valid (double check)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            Log::info('File is present and valid: ' . $request->file('image')->getClientOriginalName());

            try {
                $file = $request->file('image');
                $targetDirName = 'bienes_images'; // Directory name within the public disk
                $publicDiskPath = storage_path('app/public/' . $targetDirName); // Physical path for checks

                Log::info('Attempting to store file in public disk directory: ' . $targetDirName);
                Log::info('Physical target directory path: ' . $publicDiskPath);

                // Ensure the parent directory (storage/app/public) is writable
                if (!is_writable(storage_path('app/public'))) {
                     Log::error('Storage parent directory is NOT writable: ' . storage_path('app/public'));
                      return back()->withErrors(['upload_error' => 'Server configuration error: Storage directory permissions issue.']);
                } else {
                     Log::info('Storage parent directory IS writable.');
                }

                // Ensure the specific target directory exists and is writable
                if (!file_exists($publicDiskPath)) {
                     Log::info('Target directory does not exist, attempting to create.');
                     if (!mkdir($publicDiskPath, 0775, true)) { // Create recursively with appropriate permissions
                         Log::error('Failed to create target directory: ' . $publicDiskPath);
                         return back()->withErrors(['upload_error' => 'Server configuration error: Could not create storage directory.']);
                     }
                     Log::info('Target directory created.');
                } elseif (!is_writable($publicDiskPath)) {
                     Log::error('Target directory exists but is NOT writable: ' . $publicDiskPath);
                     return back()->withErrors(['upload_error' => 'Server configuration error: Cannot write to storage directory.']);
                } else {
                    Log::info('Target directory exists and IS writable.');
                }


                // Store the file in the 'public' disk under the 'bienes_images' directory
                // The store method returns the path relative to the disk's root (storage/app/public)
                // e.g., 'bienes_images/randomname.jpg'
                $path = $file->store($targetDirName, 'public');

                if ($path) {
                    Log::info('File stored successfully. Path returned by store(): ' . $path);
                    // Confirm physical existence (optional check)
                    if (file_exists(storage_path('app/public/' . $path))) {
                        Log::info('CONFIRMED: Physical file exists after store() call.');
                    } else {
                        Log::error('ERROR: store() returned path but physical file NOT FOUND at ' . storage_path('app/public/' . $path));
                         // Even if file not found, maybe DB record is useful? Or return error?
                        // return back()->withErrors(['upload_error' => 'File stored but could not be confirmed physically.']);
                    }

                    // Save image details to the database
                    $image = new Image();
                    $image->path = $path; // Store the relative path
                    $image->user_id = null; // Set user_id to null for anonymous uploads
                    // $image->user_id = Auth::id(); // Or assign if you require login for uploads
                    $image->save();
                    Log::info('Database record saved for image ID: ' . $image->id);

                    // Redirect back with success message
                    return back()->with('success', 'Biene sighting uploaded! Thanks, adventurer!');

                } else {
                    Log::error('File storage failed! store() returned false or null.');
                    return back()->withErrors(['upload_error' => 'Could not save the image file.']);
                }

            } catch (\Exception $e) {
                Log::error('Error during file upload process: ' . $e->getMessage());
                Log::error($e->getTraceAsString()); // Log complete stack trace
                 return back()->withErrors(['upload_error' => 'An unexpected error occurred during upload. Please check server logs.']);
            }

        } else {
             Log::warning('Upload request failed: File not present or not valid.');
             if ($request->hasFile('image')) {
                 Log::warning('File upload error code: ' . $request->file('image')->getError());
             }
             // Use the validation error message if available, otherwise a generic one
             return back()->withErrors($errors->has('image') ? $errors->all() : ['image' => 'Invalid or missing image file.']);
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
        // Double-check authorization: Ensure the logged-in user is specifically 'netraular'
        if (!Auth::check() || Auth::user()->name !== 'netraular') {
            Log::warning('Unauthorized delete attempt for image ID: ' . $image->id . ' by user: ' . (Auth::check() ? Auth::user()->name . ' (ID: ' . Auth::id() . ')' : 'Guest'));
            // Abort with a 403 Forbidden error page
            abort(403, 'Unauthorized action. Only the designated user can delete images.');
        }

        Log::info('Delete request authorized for image ID: ' . $image->id . ' by user: ' . Auth::user()->name . ' (ID: ' . Auth::id() . ')');

        try {
            // Delete the file from storage (using the 'public' disk, path is relative to it)
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
                Log::info('Deleted file from storage: ' . $image->path);
            } else {
                 Log::warning('File not found in storage for deletion: ' . $image->path . ' (Continuing to delete DB record)');
            }

            // Delete the record from the database
            $image->delete();
            Log::info('Deleted database record for image ID: ' . $image->id);

            // Redirect back with success message
            return back()->with('success', 'Biene sighting deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Error deleting image ID ' . $image->id . ': ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            // Redirect back with an error message
            return back()->withErrors(['delete_error' => 'Could not delete the image due to a server error. Please check logs.']);
        }
    }
}
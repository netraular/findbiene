<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::latest()->take(18)->get(); // Show a few more? 18?
        return view('images.index', compact('images'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        $path = $request->file('image')->store('public/bienes_images');
        // Ensure: php artisan storage:link has been run

        $image = new Image();
        $image->path = $path;
        // $image->user_id = auth()->id(); // REMOVED - No longer associating with logged-in user
        $image->user_id = null; // Explicitly set to null
        $image->save();

        return back()->with('success', 'Biene sighting uploaded! Thanks, adventurer!');
    }
}
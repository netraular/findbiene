<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ShareController extends Controller
{
    /**
     * Muestra la página para compartir/imprimir.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Simplemente retornamos la vista 'share'
        return view('share');
    }
}
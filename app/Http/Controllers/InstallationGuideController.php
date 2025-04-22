<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstallationGuideController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar la guía de instalación para el paquete de licencias
     */
    public function index()
    {
        return view('installation-guide');
    }
}

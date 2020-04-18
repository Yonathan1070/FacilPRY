<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;

/**
 * Inicio Controller, controlador que sirve en caso de que se desee
 * tener una página de inicio con las descripciones del Software.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class InicioController extends Controller
{
    /**
     * Visualiza la página de inicio del software
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        return view('general.inicio');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Operarios;
use App\Models\Validar;
use Illuminate\Http\Request;
use App\Models\Tareas;

/**
 * Clase del controlador de la pagina de inicio
 */
class InicioCtrl
{
    /**
     * Mostrar login
     *
     */
    public function mostrarLogin()
    {
        return view('inicio');
    }

    /**Mostrar el login al cerrar sesion */
    public function mostrarLoginClose()
    {

        return redirect('/login');
    }

    /**
     * Logearse
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        $oMod = new Operarios;

        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        $validador = new Validar($datosFormulario);
        $errores = $validador->validarLogin();
        if (empty($errores)) {
            
            $tareaMod = new Tareas;

            $id = $oMod->getId($datosFormulario['email']);

            $pagina = $request->input('p', 1);
            $grupo = $request->input('g', 5);

            return redirect('/admin');
        }
        return view('inicio', compact('errores', 'datosFormulario'));
    }
}

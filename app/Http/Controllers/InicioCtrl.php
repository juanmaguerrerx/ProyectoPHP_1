<?php

namespace App\Http\Controllers;

use App\Models\Operarios;
use App\Models\SessionMan;
use App\Models\Validar;
use Illuminate\Http\Request;
use App\Models\Tareas;

/**
 * Clase del controlador de la página de inicio
 */
class InicioCtrl
{
    /**
     * Mostrar login
     *
     */
    public function mostrarLogin()
    {
        $sesion = new SessionMan;
        $sesion->startSession();
        if ($sesion->existSession()) {
            return redirect('/admin');
        }

        return view('inicio');
    }

    /** Mostrar el login al cerrar sesión */
    public function mostrarLoginClose()
    {
        $sesion = new SessionMan;
        $sesion->startSession();

        // Destruir la sesión
        $sesion->destroySession();

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

            $sesion = new SessionMan;
            $sesion->setSessionDuration(120);
            $sesion->startSession();
            $sesion->write('id', $id);

            $pagina = $request->input('p', 1);
            $grupo = $request->input('g', 5);

            return redirect('/admin');
        }

        return view('inicio', compact('errores', 'datosFormulario'));
    }
}

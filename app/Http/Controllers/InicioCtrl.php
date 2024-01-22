<?php

namespace App\Http\Controllers;

use App\Models\Operarios;
use App\Models\Validar;
use Illuminate\Http\Request;
use App\Models\Tareas;

class InicioCtrl
{
    public function mostrarLogin()
    {
        return view('inicio');
    }

    public function mostrarLoginClose()
    {

        return redirect('/login');
    }

    public function login(Request $request)
    {
        $oMod = new Operarios;

        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        $validador = new Validar($datosFormulario);
        $errores = $validador->validarLogin();
        if (empty($errores)) {
            session_start();
            $tareaMod = new Tareas;

            $id = $oMod->getId($datosFormulario['email']);
            // dd($id);
            // dd($_SESSION['id']);
            // $tareasBase = $tareaMod->getTareas($_SESSION['id']);
            // dd($tareas);
            $pagina = $request->input('p', 1);
            $grupo = $request->input('g', 5);

            return redirect('/admin');
        }
        return view('inicio', compact('errores', 'datosFormulario'));
    }
}

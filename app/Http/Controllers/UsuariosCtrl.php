<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Validar\ConexionDB;
use App\Models\Operarios;
use App\Models\Validar;

class UsuariosCtrl
{
    public function mostrarTablaUsuarios(Request $request)
    {

        // $id = $_SESSION['user_id']; 
        $o = new Operarios;


        $searchNombre = $request->input('search','');
        $rol = $request->input('rol','');

        $operariosBase = $o->getOperarios($searchNombre,$rol);

        $pagina = $request->input('p', 1);
        $grupo = $request->input('g', 5);

        $operarios = $o->getOperariosPag($operariosBase, $pagina, $grupo);
        // dd($operarios);

        return view('usuarios', compact('operarios', 'pagina', 'grupo', 'operariosBase','searchNombre','rol'));
    }
    public function enviarFormModUser(Request $request)
    {
        $idOperario = $request->input('id');
        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        $validador = new Validar($datosFormulario);
        $errores = $validador->validarUsuarioMod();

       

        $o = new Operarios;
        
        if (empty($errores)) {
            $respuesta = $o->modOperario($idOperario, $datosFormulario);
            if ($respuesta) {
                $operariosBase = $o->getOperarios();
                $pagina = $request->input('p', 1);
                $grupo = $request->input('g', 5);

                $operarios = $o->getOperariosPag($operariosBase, $pagina, $grupo);

                return redirect('/users')->with(compact('operarios', 'operariosBase', 'pagina', 'grupo'));
            } else {
                dd($respuesta);
            }
        }
        return view('modUser', compact('errores', 'datosFormulario'));
    }

    public function enviarOperario(Request $request)
    {
        $operarios = array();
        $o = new Operarios;
        $operarios = $o->getOperarios();

        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        $validador = new Validar($datosFormulario);
        $errores = $validador->validarUsuario();
        // dd($datosFormulario);

        if (empty($errores)) {
            $respuesta = $o->crearOperario($datosFormulario);
            // dd($respuesta);
            if ($respuesta) {
                $operariosBase = $o->getOperarios();
                $pagina = $request->input('p', 1);
                $grupo = $request->input('g', 5);

                $operarios = $o->getOperariosPag($operariosBase, $pagina, $grupo);
                return view('usuarios', compact('operarios', 'operariosBase', 'pagina', 'grupo'));
            } else {
                print_r($respuesta);
            }
        }

        // Retorna la vista con errores y datos del formulario
        return view('formUser', compact('errores', 'datosFormulario'));
    }



    public function mostrarFormModUser(Request $request)
    {
        $oMod = new Operarios;
        $idOperario = $request->input('id');
        // dd($idOperario);
        $datosFormulario = $oMod->getOperario($idOperario);
        // dd($datosOperario);
        return view('modUser', compact('datosFormulario'));
    }

    public function mostrarFormUser()
    {
        return view('formUser');
    }

    public function deleteUser(Request $request)
    {
        $oMod = new Operarios;
        $idOperario = $request->input('id');
        $datosFormulario = $oMod->getOperario($idOperario);

        return view('deleteUser', compact('datosFormulario'));
    }

    public function confirmDeleteUser(Request $request)
    {
        $idOperario = $request->input('id');
        $o = new Operarios;

        $respuesta = $o->deleteOperario($idOperario);

        if ($respuesta) {
            $operariosBase = $o->getOperarios();
            $pagina = $request->input('p', 1);
            $grupo = $request->input('g', 5);

            $operarios = $o->getOperariosPag($operariosBase, $pagina, $grupo);

            return redirect('/users')->with(compact('operarios', 'operariosBase', 'pagina', 'grupo'));
        } else dd('error');
        return view('deleteUser');
    }
}

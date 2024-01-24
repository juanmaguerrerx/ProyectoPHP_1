<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Validar\ConexionDB;
use App\Models\Operarios;
use App\Models\SessionMan;
use App\Models\Validar;

/**
 * Controlador de usuarios
 */
class UsuariosCtrl
{




    /**
     * Muestra la tabla Operarios
     *
     * @param Request $request
     */
    public function mostrarTablaUsuarios(Request $request)
    {

        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;
        // dd($o->esAdmin($id),$id);
        if (!$o->esAdmin($id)) {
            return redirect('/admin');
        }
        $searchNombre = $request->input('search', '');
        $rol = $request->input('rol', '');

        $operariosBase = $o->getOperarios($searchNombre, $rol);

        $pagina = $request->input('p', 1);
        $grupo = $request->input('g', 5);

        $operarios = $o->getOperariosPag($operariosBase, $pagina, $grupo);

        return view('usuarios', compact('operarios', 'pagina', 'grupo', 'operariosBase', 'searchNombre', 'rol'));
    }

    /**
     * Envia el formulario de modificar usuraio
     *
     * @param Request $request
     */
    public function enviarFormModUser(Request $request)
    {

        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($sesion->read('id'))) {
            return redirect('/admin');
        }

        $idOperario = $request->input('id');
        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        $validador = new Validar($datosFormulario);
        $errores = $validador->validarUsuarioMod();



        $o = new Operarios;

        //Si no hay errores
        if (empty($errores)) {
            $respuesta = $o->modOperario($idOperario, $datosFormulario);

            //Si se modifica
            if ($respuesta) {
                $operariosBase = $o->getOperarios();

                $searchNombre = $request->input('search', '');
                $rol = $request->input('rol', '');
                $pagina = $request->input('p', 1);
                $grupo = $request->input('g', 5);

                $operarios = $o->getOperariosPag($operariosBase, $pagina, $grupo);

                return redirect('/users')->with(compact('operarios', 'operariosBase', 'pagina', 'grupo', 'rol', 'searchNombre'));
            } else { //Si no se modifica
                dd($respuesta);
            }
        }
        return view('modUser', compact('errores', 'datosFormulario'));
    }

    /**
     * Funcion que crea operario
     *
     * @param Request $request
     */
    public function enviarOperario(Request $request)
    {

        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)) {
            return redirect('/admin');
        }

        $operarios = array();
        $o = new Operarios;
        $operarios = $o->getOperarios();

        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        $validador = new Validar($datosFormulario);
        $errores = $validador->validarUsuario();

        //Si no hay errores
        if (empty($errores)) {
            $respuesta = $o->crearOperario($datosFormulario);

            // Si se crea
            if ($respuesta) {
                $operariosBase = $o->getOperarios();

                $searchNombre = $request->input('search', '');
                $rol = $request->input('rol', '');
                $pagina = $request->input('p', 1);
                $grupo = $request->input('g', 5);

                $operarios = $o->getOperariosPag($operariosBase, $pagina, $grupo);
                return view('usuarios', compact('operarios', 'operariosBase', 'pagina', 'grupo', 'rol', 'searchNombre'));
            } else { //Si no se crea
                print_r($respuesta);
            }
        }

        return view('formUser', compact('errores', 'datosFormulario'));
    }


    /**
     * Muestra el formulario de edicion de operario
     *
     * @param Request $request
     */
    public function mostrarFormModUser(Request $request)
    {

        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)) {
            return redirect('/admin');
        }

        $oMod = new Operarios;

        $idOperario = $request->input('id');
        $datosFormulario = $oMod->getOperario($idOperario);

        return view('modUser', compact('datosFormulario'));
    }

    /**
     * Muestra el formulario para añadir operarios
     *
     */
    public function mostrarFormUser()
    {
        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)) {
            return redirect('/admin');
        }

        return view('formUser');
    }

    /**
     * Muestra el formulario para borrar operarios
     *
     * @param Request $request
     */
    public function deleteUser(Request $request)
    {
        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)) {
            return redirect('/admin');
        }

        $oMod = new Operarios;
        $idOperario = $request->input('id');
        $datosFormulario = $oMod->getOperario($idOperario);

        return view('deleteUser', compact('datosFormulario'));
    }

    /**
     * Borra el operario seleccionado
     *
     * @param Request $request
     */
    public function confirmDeleteUser(Request $request)
    {
        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)) {
            return redirect('/admin');
        }

        $idOperario = $request->input('id');
        $o = new Operarios;

        $respuesta = $o->deleteOperario($idOperario);

        //Si lo borra
        if ($respuesta) {

            $operariosBase = $o->getOperarios();

            $searchNombre = $request->input('search', '');
            $pagina = $request->input('p', 1);
            $grupo = $request->input('g', 5);
            $rol = $request->input('rol', '');

            $operarios = $o->getOperariosPag($operariosBase, $pagina, $grupo);

            return redirect('/users')->with(compact('operarios', 'operariosBase', 'pagina', 'grupo', 'rol', 'searchNombre'));

            //Si no lo borra
        } else dd('error');

        return view('deleteUser');
    }
}

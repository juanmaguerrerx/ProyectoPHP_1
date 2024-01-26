<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConexionDB;
use App\Models\Operarios;
use App\Models\Provincias;
use App\Models\Tareas;
use App\Models\Validar;
use DateTime;
use App\Models\SessionMan;

/**
 * Controlador de Tareas
 */
class TareasCtrl
{
    
    /**
     * Funcion para mostrar formulario para crear tarea
     *
     */
    public function mostrarForm()
    {
        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)){
            return redirect('/admin');
        }

        $operarios = array();
        $provincias = array();

        $opMod = new Operarios;
        $operarios = $opMod->getOperarios();

        $provMod = new Provincias;
        $provincias = $provMod->getProvincias();

        return view('form', compact('operarios', 'provincias'));
    }

    /**
     * Enviar el formulario para crear tarea
     *
     * @param Request $request
     */
    public function enviarForm(Request $request)
    {
        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)){
            return redirect('/admin');
        }

        $sesion = new SessionMan;
        $sesion->startSession();

        $id = $sesion->read('id');

        $operarios = array();
        $provincias = array();

        $opMod = new Operarios;
        $operarios = $opMod->getOperarios();

        $provMod = new Provincias;
        $provincias = $provMod->getProvincias();

        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        $validador = new Validar($datosFormulario);
        $errores = $validador->validarTarea();

        // Si no hay errores
        if (empty($errores)) {
            $tareaMod = new Tareas;
            $respuesta = $tareaMod->crearTarea($datosFormulario);

            // Si hay respuesta (tarea creada)
            if ($respuesta) {

                $filtro = $request->input('f', '');

                $tareasBase = $tareaMod->getTareas($id, $filtro);



                $pagina = $request->input('p', 1);
                $grupo = $request->input('g', 5);

                $tareas = $tareaMod->getTareasPag($tareasBase, $pagina, $grupo);
                return redirect('/admin')->with(compact('tareas', 'tareasBase', 'pagina', 'grupo', 'filtro'));
            } else { //Si no hay respuesta
                print_r($respuesta);
            }
        }

        // Dvuelve la vista con errores, datos del formulario y más datos necesarios
        return view('form', compact('operarios', 'provincias', 'errores', 'datosFormulario'));
    }

    /**
     * Controlador de borrar tarea seleccionada
     *
     * @param Request $request
     */
    public function deleteTarea(Request $request)
    {
        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)){
            return redirect('/admin');
        }

        $tMod = new Tareas;
        $idTarea = $request->input('id');
        $datosFormulario = $tMod->getTarea($idTarea);

        return view('deleteTarea', compact('datosFormulario'));
    }

    /**
     * El POST de borrar tarea
     *
     * @param Request $request
     */
    public function confirmDeleteTarea(Request $request)
    {
        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $o = new Operarios;

        if (!$o->esAdmin($id)){
            return redirect('/admin');
        }


        $idTarea = $request->input('id');
        $t = new Tareas;

        $filtro = $request->input('f');
        $respuesta = $t->deleteTarea($idTarea);

        if ($respuesta) {
            $tareasBase = $t->getTareas($id, $filtro); //ID -> SESSION 

            $pagina = $request->input('p', 1);
            $grupo = $request->input('g', 5);

            $tareas = $t->getTareasPag($tareasBase, $pagina, $grupo);

            return redirect('/admin')->with(compact('tareas', 'tareasBase', 'pagina', 'grupo', 'filtro'));
        } else return view('deleteTarea');
    }

    /**
     * Modificar tarea seleccionada
     *
     * @param Request $request
     */
    public function modTarea(Request $request)
    {   
        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $tMod = new Tareas;
        $pMod = new Provincias;
        $oMod = new Operarios;
        $idTarea = $request->input('id');
        // dd($idTarea);
        $datosFormulario = $tMod->getTarea($idTarea);
        $provincias = $pMod->getProvincias();
        $operarios = $oMod->getOperarios();
        return view('modTarea', compact('datosFormulario', 'provincias', 'operarios'));
    }

    /**
     * POST modificar tarea
     *
     * @param Request $request
     */
    public function confirmModTarea(Request $request)
    {

        $sesion = new SessionMan;
        $sesion->existSession();
        $sesion->startSession();
        $id = $sesion->read('id');

        $idTarea = $request->input('id');
        $filtro = $request->input('f');

        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        // dd($datosFormulario);

        $validador = new Validar($datosFormulario);
        $errores = $validador->validarTareaMod();

        $pMod = new Provincias;
        $oMod = new Operarios;
        $provincias = $pMod->getProvincias();
        $operarios = $oMod->getOperarios();

        $t = new Tareas;
        
        // Si no hay errores
        if (empty($errores)) {            
            $respuesta = $t->modTarea($idTarea, $datosFormulario);
            // Si se ha modificado
            if ($respuesta) {
                $tareasBase = $t->getTareas($id); //ID SESSION

                $pagina = $request->input('p', 1);
                $grupo = $request->input('g', 5);

                $tareas = $t->getTareasPag($tareasBase, $pagina, $grupo);

                return redirect('/admin')->with(compact('tareas', 'tareasBase', 'pagina', 'grupo', 'filtro', 'operarios', 'provincias'));
            } else { //Si no se ha modificado
                dd($respuesta);
            }
        }

        // Si hay errores
        return view('modTarea', compact('errores', 'datosFormulario', 'provincias', 'operarios'));
    }
}

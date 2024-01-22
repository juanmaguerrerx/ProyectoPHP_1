<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConexionDB;
use App\Models\Operarios;
use App\Models\Provincias;
use App\Models\Tareas;
use App\Models\Validar;

class TareasCtrl
{

    //
    public function mostrarForm()
    {
        $operarios = array();
        $provincias = array();

        $opMod = new Operarios;
        $operarios = $opMod->getOperarios();

        $provMod = new Provincias;
        $provincias = $provMod->getProvincias();
        return view('form', compact('operarios', 'provincias'));
    }
    public function enviarForm(Request $request)
    {

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

        if (empty($errores)) {
            $tareaMod = new Tareas;
            $respuesta = $tareaMod->crearTarea($datosFormulario);

            if ($respuesta) {
                $tareasBase = $tareaMod->getTareas(1);
                // dd($tareas);
                $pagina = $request->input('p', 1);
                $grupo = $request->input('g', 5);

                $tareas = $tareaMod->getTareasPag($tareasBase, $pagina, $grupo);
                return redirect('/admin')->with(compact('tareas','tareasBase','pagina','grupo'));
            } else {
                print_r($respuesta);
            }
        }

        // Retorna la vista con errores y datos del formulario
        return view('form', compact('operarios', 'provincias', 'errores', 'datosFormulario'));
    }

    public function deleteTarea(Request $request){
        $tMod = new Tareas;
        $idTarea = $request->input('id');
        // dd($idTarea);
        $datosFormulario = $tMod->getTarea($idTarea);
        // dd($datosFormulario);

        return view('deleteTarea',compact('datosFormulario'));
    }

    public function confirmDeleteTarea(Request $request){
        $idTarea = $request->input('id');
        $t = new Tareas;

        $respuesta = $t->deleteTarea($idTarea);

        if ($respuesta) {
            $tareasBase = $t->getTareas(2); //ID -> SESSION 
            $pagina = $request->input('p', 1);
            $grupo = $request->input('g', 5);

            $tareas = $t->getTareasPag($tareasBase, $pagina, $grupo);

            return redirect('/admin')->with(compact('tareas', 'tareasBase', 'pagina', 'grupo'));
        } else dd('error');
        return view('deleteTarea');
    }

    public function modTarea(Request $request){
        $tMod = new Tareas;
        $pMod = new Provincias;
        $oMod = new Operarios;
        $idTarea = $request->input('id');
        // dd($idTarea);
        $datosFormulario = $tMod->getTarea($idTarea);
        $provincias = $pMod->getProvincias();
        $operarios = $oMod->getOperarios();
        //dd($datosFormulario);
        return view('modTarea', compact('datosFormulario','provincias','operarios'));
    }
    public function confirmModTarea(Request $request){
        $idTarea = $request->input('id');
        // dd($idTarea);
        $datosFormulario = $request->except('_token');
        $datosFormulario = array_map('trim', $datosFormulario);
        $validador = new Validar($datosFormulario);
        $errores = $validador->validarTareaMod();
        // dd($errores);
        // dd($datosFormulario);
        $pMod = new Provincias;
        $oMod = new Operarios;

        $provincias = $pMod->getProvincias();
        $operarios = $oMod->getOperarios();

        $t = new Tareas;
        // $datosOperario = $o->getOperario($idOperario);
        if (empty($errores)) {
            $fecha_realizacion=NULL;
            if($datosFormulario['estado']=='R' || $datosFormulario['estado']=='B'){
                $fecha_realizacion=date("Y-m-d H:i:s");
            }
            // dd($fecha_realizacion);
            $respuesta = $t->modTarea($idTarea, $datosFormulario, $fecha_realizacion);
            // dd($respuesta);
            if ($respuesta) {
                $tareasBase = $t->getTareas(1); //ID SESSION
                $pagina = $request->input('p', 1);
                $grupo = $request->input('g', 5);

                $tareas = $t->getTareasPag($tareasBase, $pagina, $grupo);

                return redirect('/admin')->with(compact('tareas', 'tareasBase', 'pagina', 'grupo','operarios','provincias'));
            } else {
                dd($respuesta);
            }
        }
        return view('modTarea', compact('errores', 'datosFormulario','provincias','operarios'));
    }
}

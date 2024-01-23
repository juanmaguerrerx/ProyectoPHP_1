<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Validar\ConexionDB;
use App\Models\Tareas;
use App\Models\Operarios;

/**
 * Controlador de la pagina '/admin'
 */
class AdminCtrl
{
    /**
     * Mostrar la tabla de tareas
     *
     * @param Request $request
     * 
     */
    public function mostrarTabla(Request $request)
    {

        $t = new Tareas;

        $orderFecha = $request->input('order', '');

        $pagina = $request->input('p', 1);
        $grupo = $request->input('g', 5);
        $filtro = $request->input('f', '');
        $filtroName = $request->input('n', '');
        $tareasBase = $t->getTareas(2, $filtro, $filtroName, $orderFecha); //ID DEL OPERARIO (SESSION)

        $oMod = new Operarios;
        $operarios = $oMod->getOperarios();

        $tareas = $t->getTareasPag($tareasBase, $pagina, $grupo);

        return view('tabla', compact('tareas', 'pagina', 'grupo', 'tareasBase', 'filtro', 'filtroName', 'operarios', 'orderFecha'));
    }
}

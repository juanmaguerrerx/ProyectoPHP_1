<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Validar\ConexionDB;
use App\Models\Tareas;
use App\Models\Operarios;

class AdminCtrl
{
    //
    public function mostrarTabla(Request $request)
    {

        // $id = $_SESSION['user_id']; 
        $t = new Tareas;
       
        // dd($tareas);

        $pagina = $request->input('p',1);
        $grupo = $request->input('g',5);
        $filtro = $request->input('f','');
        $filtroName = $request->input('n','');
        $tareasBase = $t->getTareas(2,$filtro,$filtroName);

        $oMod = new Operarios;
        $operarios = $oMod->getOperarios();

        $tareas = $t->getTareasPag($tareasBase,$pagina,$grupo);

        return view('tabla', compact('tareas','pagina','grupo','tareasBase','filtro','filtroName','operarios'));
    }
}

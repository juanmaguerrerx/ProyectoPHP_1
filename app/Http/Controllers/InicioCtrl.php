<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioCtrl 
{
    public function mostrarLogin(){
        return view('inicio');
    }

}

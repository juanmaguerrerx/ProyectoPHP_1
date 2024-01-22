<?php

use App\Http\Controllers\AdminCtrl;
use App\Http\Controllers\InicioCtrl;
use App\Http\Controllers\TareasCtrl;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [InicioCtrl::class,'mostrarLogin']);
Route::post('/');

Route::get('/form',[TareasCtrl::class,'mostrarForm']);
Route::post('/form',[TareasCtrl::class,'enviarForm',]);

Route::get('/admin',[AdminCtrl::class,'mostrarTabla']);
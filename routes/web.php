<?php

use App\Http\Controllers\AdminCtrl;
use App\Http\Controllers\InicioCtrl;
use App\Http\Controllers\TareasCtrl;
use App\Http\Controllers\UsuariosCtrl;
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


Route::redirect('','/login');
Route::get('/login', [InicioCtrl::class,'mostrarLogin'])->name('inicio');
Route::post('/login',[InicioCtrl::class,'login'])->name('login');

Route::get('/logout',[InicioCtrl::class,'mostrarLoginClose'])->name('logout');

Route::get('/form',[TareasCtrl::class,'mostrarForm'])->name('form');
Route::post('/form',[TareasCtrl::class,'enviarForm'])->name('enviarForm');

Route::get('/admin',[AdminCtrl::class,'mostrarTabla'])->name('tabla');

Route::get('/users',[UsuariosCtrl::class,'mostrarTablaUsuarios'])->name('tablaUsuarios');

Route::get('/formUser',[UsuariosCtrl::class,'mostrarFormUser'])->name('formUser');
Route::post('/formUser',[UsuariosCtrl::class,'enviarOperario'])->name('enviarOperario');

Route::get('/formModUser',[UsuariosCtrl::class,'mostrarFormModUser'])->name('formModUser');
Route::post('/formModUser',[UsuariosCtrl::class,'enviarFormModUser'])->name('enviarFormModUser');

Route::get('/deleteUser',[UsuariosCtrl::class,'deleteUser'])->name('deleteUser');
Route::post('/deleteUser',[UsuariosCtrl::class,'confirmDeleteUser'])->name('confirmDeleteUser');

Route::get('/modTarea',[TareasCtrl::class,'modTarea'])->name('modTarea');
Route::post('/modTarea',[TareasCtrl::class,'confirmModTarea'])->name('confirmModTarea');

Route::get('/deleteTarea',[TareasCtrl::class,'deleteTarea'])->name('deleteTarea');
Route::post('/deleteTarea',[TareasCtrl::class,'confirmDeleteTarea'])->name('confirmDeleteTarea');

Route::get('/verTarea',[TareasCtrl::class,'verTarea'])->name('verTarea');
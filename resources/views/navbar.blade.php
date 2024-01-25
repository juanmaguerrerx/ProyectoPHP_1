<style>
    .m {
        margin-right: 2vh;
    }
</style>

@php
    use App\Models\SessionMan;
    use App\Models\Operarios;
    $sesion = new SessionMan();
    $op = new Operarios();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    {{-- <div class="navbar-brand">BungleBuilding S.L</div> --}}
    <div class="navbar-brand">
        <strong>BungleBuilding S.L</strong>
    </div>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            @if ($op->esAdmin($sesion->read('id'))['admin'])
                @if (request()->is('users'))
                    <li class="nav-item">
                        <a href="{{ route('formUser') }}"><button type="button" class="btn btn-success m">Añadir
                                Operario</button></a>
                    </li>
                @endif
                @if (request()->is('admin'))
                    <li class="nav-item">
                        <a href="{{ route('form') }}"><button type="button" class="btn btn-success m">Añadir
                                Tarea</button></a>
                    </li>
                @endif
                @if (!request()->is('users'))
                    {{-- Comprobar tambien si es admin --}}
                    <li class="nav-item">
                        <a href="{{ route('tablaUsuarios', ['p' => 1, 'g' => 5]) }}"><button type="button"
                                class="btn btn-outline-warning m">Ver Operarios</button></a>
                    </li>
                @endif
            @endif
            @if (!request()->is('admin'))
                <li class="nav-item">
                    <a href="{{ route('tabla', ['p' => 1, 'g' => 5]) }}"><button type="button"
                            class="btn btn-outline-success m">Ver Tareas</button></a>
                </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('logout') }}"><button type="button" class="btn btn-outline-danger">Cerrar
                        Sesión</button></a>
            </li>
        </ul>
    </div>
</nav>

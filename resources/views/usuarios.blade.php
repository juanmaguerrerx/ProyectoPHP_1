<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="public\images\logo.jpg" type="image/x-icon">
    <title>Operarios | BungleBuilding S.L</title>
    <style>
        .marg {
            margin-top: 10vh;
        }

        .l {
            margin-top: 0.25vh;
            margin-right: 1px;
        }

        .cen {
            text-align: center;
        }

        .pag {
            margin: 0px auto;
        }

        .table {
            width: 100%;
            text-align: left;
            font-size: small;
        }
    </style>

    {{-- @php
    if(!$_SESSION['admin']){
        redirect()->route('/');
    }   
   @endphp --}}



    <!-- Enlace a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>
    @include('navbar')
    <div class="container-fluid marg">
        <h3 class="text-center">Lista de Operarios</h3>

        <form action="{{ url('/users') }}" method="get">
            <label for="grupo">Elementos por Página:</label>
            <select name="g" id="grupo" onchange="this.form.submit()">
                <option value="5" {{ $grupo == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ $grupo == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ $grupo == 15 ? 'selected' : '' }}>15</option>
                <option value="25" {{ $grupo == 25 ? 'selected' : '' }}>25</option>
            </select>
        </form>

        <form action="{{url('/users')}}" method="get">
            <label for="seach">Buscar:</label>
            <input type="text" name="search" id="search" value="{{isset($searchNomrbe) ? $searchNombre : ''}}">
            <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
        </form>

        <div class="container-xxl">
            <!-- Tabla Bootstrap -->
            <table class="table table-bordered">
                <!-- Encabezado de la tabla -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th><strong>OPCIONES</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operarios as $operario)
                        @php
                            if ($operario['admin']) {
                                $rol = 'Admin';
                            } else {
                                $rol = 'Operario';
                            }
                        @endphp
                        <tr>
                            <td>{{ isset($operario['id']) ? $operario['id'] : '' }}</td>
                            <td>{{ isset($operario['nombre']) ? $operario['nombre'] : '' }}</td>
                            <td>{{ isset($operario['apellidos']) ? $operario['apellidos'] : '' }}</td>
                            <td>{{ isset($operario['correo']) ? $operario['correo'] : '' }}</td>
                            <td>{{ isset($operario['contrasena']) ? $operario['contrasena'] : '' }}</td>
                            <td>{{ isset($operario['admin']) ? $rol : '' }}</td>
                            <td><a href="{{ url('/formModUser?id=' . $operario['id']) }}">
                                    @if (isset($operario['id']))
                                        <button class="btn btn-warning l">Modificar</button>
                                </a><a href="{{ url('deleteUser?id=' . $operario['id']) }}"><button
                                        class="btn btn-danger l">Eliminar</button></a></td>
                    @endif
                    </tr>
                    @endforeach
                </tbody>

            </table>
            <div class="container-fluid cen">

                @if ($pagina > 1)
                    <a class="pag" href="{{ url('/users?p=' . ($pagina - 1) . '&g=' . $grupo) }}"><button
                            class="btn btn-outline-primary">Anterior</button></a>
                @endif
                <button disabled class="btn btn-primary">{{ $pagina }}</button>
                @if (count($operarios) == $grupo && count($operariosBase) > $pagina * $grupo)
                    <!-- Mostrar el botón Siguiente solo si hay mas tareas para mostrar -->
                    <a class="pag" href="{{ url('/users?p=' . ($pagina + 1) . '&g=' . $grupo) }}"><button
                            class="btn btn-outline-primary">Siguiente</button></a>
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="public\images\logo.jpg" type="image/x-icon">
    <title>Tareas | BungleBuilding S.L</title>
    <style>
        .marg {
            margin-top: 10vh;
        }

        .l {
            margin-top: 0.25vh;
        }

        .B {
            background-color: rgb(223, 185, 115);
        }

        .C {
            background-color: rgb(255, 60, 60);
        }

        .R {
            background-color: rgb(51, 220, 51);
        }

        .P {
            background-color: rgb(114, 114, 244);
        }

        .cen {
            text-align: center;
        }

        .rig {
            text-align: end;
        }

        .pag {
            margin: 0px auto;
        }

        .table {
            width: 100%;
            text-align: left;
            font-size: small;
        }

        .b {
            margin-bottom: 1vh;

        }

        .if {
            margin-left: 2%;
            margin-right: 2%;
        }
    </style>

    <!-- Enlace a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    @include('navbar')
    <div class="container-fluid marg">
        <h1 class="text-center">Lista de Tareas</h1>
        {{-- @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif --}}

        <form action="{{ url('/admin') }}" method="get">
            <fieldset>
                <legend>Filtros</legend>
                <label for="filtro">Estado</label>
                <select name="f" id="filtro">
                    <option value="" selected>-Todos-</option>
                    <option value="R" {{ $filtro == 'R' ? 'selected' : '' }}>Realizada</option>
                    <option value="P" {{ $filtro == 'P' ? 'selected' : '' }}>En proceso</option>
                    <option value="C" {{ $filtro == 'C' ? 'selected' : '' }}>Cancelada</option>
                    <option value="B" {{ $filtro == 'B' ? 'selected' : '' }}>Esperando aprobacion</option>
                </select>
                <br>
                <label for="name">Operario Encargado</label>
                <select name="n" id="nombre">
                    <option value="" selected>-Todos-</option>
                    @foreach ($operarios as $operario)
                        @if ($operario['admin'] == 1)
                        @else
                            <option value="{{ $operario['id'] }}"
                                {{ $filtroName == $operario['id'] ? 'selected' : '' }}>
                                {{ $operario['nombre'] . ' ' . $operario['apellidos'] }}</option>
                        @endif
                    @endforeach
                </select>
                <br>
                <label for="order">Ordenar por</label>
                <select name="order" id="order">
                    <option value="" {{ $orderFecha == '' ? 'selected' : '' }}>-Ordenar-</option>
                    <option value="fC" {{ $orderFecha == 'fC' ? 'selected' : '' }}>Fecha de Creación</option>
                    <option value="fR" {{ $orderFecha == 'fR' ? 'selected' : '' }}>Fecha de Realización</option>
                </select>
                <br>
                <label for="grupo">Elementos por Página:</label>
                <select name="g" id="grupo">
                    <option value="5" {{ $grupo == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $grupo == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ $grupo == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ $grupo == 25 ? 'selected' : '' }}>25</option>
                </select>
            </fieldset>



            <button type="submit" class="btn btn-outline-secondary b">Aplicar Filtros</button>


        </form>

        <div class="container-xxl">
            <!-- Tabla Bootstrap -->
            <table class="table table-bordered" id="tbl">
                <!-- Encabezado de la tabla -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NIF/CIF Cliente</th>
                        <th>Nombre Cliente</th>
                        <th>Teléfono Contacto</th>
                        <th>Email</th>
                        <th>Descripcion</th>
                        <th>Provincia</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th>Operario Encargado</th>
                        <th>Fecha Realización</th>
                        <th>Anotaciones Anteriores</th>
                        <th>Anotaciones Posteriores</th>
                        <th><strong>OPCIONES</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tareas as $tarea)
                        @php
                            switch ($tarea['estado']) {
                                case 'P':
                                    $st = 'En proceso';
                                    break;
                                case 'C':
                                    $st = 'Cancelada';
                                    break;
                                case 'R':
                                    $st = 'Realizada';
                                    break;
                                case 'B':
                                    $st = 'Esperando aprobacion...';
                                    break;
                            }
                        @endphp
                        <tr>
                            {{-- @dd($tarea); --}}
                            <td>{{ isset($tarea['id']) ? $tarea['id'] : '' }}</td>
                            <td>{{ isset($tarea['nif_cliente']) ? $tarea['nif_cliente'] : '' }}</td>
                            <td>{{ isset($tarea['nombre_cliente']) ? $tarea['nombre_cliente'] : '' }}</td>
                            <td>{{ isset($tarea['telefono_cliente']) ? $tarea['telefono_cliente'] : '' }}</td>
                            <td>{{ isset($tarea['correo_cliente']) ? $tarea['correo_cliente'] : '' }}</td>
                            <td>{{ isset($tarea['descripcion']) ? $tarea['descripcion'] : '' }}</td>
                            <td>{{ isset($tarea['provincia']) ? $tarea['provincia'] : '' }}</td>
                            <td class="{{ $tarea['estado'] }}">@php echo $st @endphp</td>
                            <td>{{ isset($tarea['fecha_creacion']) ? $tarea['fecha_creacion'] : '' }}</td>
                            <td>{{ isset($tarea['operario']) ? $tarea['operario'] : '' }}</td>
                            <td>
                                @if ($tarea['fecha_realizacion'] == '-0001-11-30' || $tarea['fecha_realizacion'] == null)
                                    ~
                                @else
                                    {{ $tarea['fecha_realizacion'] }}
                                @endif
                            </td>
                            <td>{{ isset($tarea['anotaciones_anteriores']) ? $tarea['anotaciones_anteriores'] : '' }}
                            </td>
                            <td>{{ isset($tarea['anotaciones_posteriores']) ? $tarea['anotaciones_posteriores'] : '' }}
                            </td>
                            <td><a href="{{ url('modTarea?id=' . $tarea['id']) }}">
                                    @if (isset($tarea['id']))
                                        <button class="btn btn-warning btn-sm l">Modificar</button>
                                </a><a href="{{ url('deleteTarea?id=' . $tarea['id']) }}"><button
                                        class="btn btn-danger btn-sm l">Eliminar</button></a></td>
                    @endif
                    </tr>
                    @endforeach
                </tbody>

            </table>

            {{-- <form action="{{url('/admin')}}" method="get">
                <button type="submit" class="btn btn-outline-primary" value="{{$pagina-1}}">Anterior</button>
                <button disabled class="btn btn-primary">{{ $pagina }}</button>
                <button type="submit" class="btn btn-outline-primary" value="{{ $pagina+1 }}">Siguiente</button>
            </form> --}}


            {{-- Paginacion --}}
            <form action="{{ url('/admin') }}" method="GET" class="container-fluid cen">

                @php
                    $ultimaPag = intval(count($tareasBase) / $grupo + 1);
                @endphp

                @if ($pagina > 1)
                    <button type="submit" name="p" value='1'
                        class="btn btn-outline-secondary if">Inicio</button>
                    <button type="submit" name="p" value="{{ $pagina - 1 }}"
                        class="btn btn-outline-primary">Anterior</button>
                @endif

                <button disabled class="btn btn-primary">{{ $pagina }}</button>

                @if (count($tareas) == $grupo && count($tareasBase) > $pagina * $grupo)
                    <!-- Mostrar el botón Siguiente solo si hay más tareas para mostrar -->
                    <button type="submit" name="p" value="{{ $pagina + 1 }}"
                        class="btn btn-outline-primary">Siguiente</button>
                @endif

                @if (!count($tareasBase) % $grupo == 0)
                    <button type="submit" name="p" value="{{ $ultimaPag }}"
                        class="btn btn-outline-secondary if">Fin</button>
                @endif

                <input type="hidden" name="g" value="{{ $grupo }}">
            </form>

        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>

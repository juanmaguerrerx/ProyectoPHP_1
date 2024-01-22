<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Operario | BungleBuilding S.L</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: grey;
            color: white;
        }

        select>option {
            color: black;
        }

        .mg {
            margin-bottom: 5vh;
        }

        .pad {
            padding: 5vh;
        }

        /* Estilo para el asterisco rojo */
        .required::after {
            content: ' *';
            color: red;
        }

        fieldset {
            padding: 1%;
            margin-bottom: 2%;
        }

        legend {
            margin-left: 0%;
        }

        .aste {
            color: red;
        }
    </style>
</head>

<body>
    {{-- incluir el navbar  --}}
    @include('navbar')
    <div class="container mt-5 pad">
        <h2 class="tp">Modificar Usuario</h2>
        <p>&#40; <span class="aste">&#42;</span> son campos obligatorios&#41;</p>

        <form method='POST' action="{{ route('enviarFormModUser') }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="hidden" name="id" value="{{ $datosFormulario['id'] }}">
                    <label for="nombre" class="required">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                        value="{{ isset($datosFormulario['nombre']) ? $datosFormulario['nombre'] : '' }}" id="nombre"
                        placeholder="Nombre">
                    @if (isset($errores['nombre']))
                        <div class="text-danger">{{ $errores['nombre'] }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="apellidos" class="required">Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" id="apellidos"
                        value="{{ isset($datosFormulario['apellidos']) ? $datosFormulario['apellidos'] : '' }}"
                        placeholder="Apellidos">
                    @if (isset($errores['apellidos']))
                        <div class="text-danger">{{ $errores['apellidos'] }}</div>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="correo" class="required">Correo Electrónico</label>
                    <input type="text" name="correo" class="form-control" id="correo"
                        value="{{ isset($datosFormulario['correo']) ? $datosFormulario['correo'] : '' }}"
                        placeholder="Email">
                    @if (isset($errores['correo']))
                        <div class="text-danger">{{ $errores['correo'] }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="contrasena" class="required">Contraseña</label>
                    <input type="text" name="contrasena" class="form-control" id="contrasena"
                        value="{{ isset($datosFormulario['contrasena']) ? $datosFormulario['contrasena'] : '' }}"
                        placeholder="Contraseña">
                    @if (isset($errores['contrasena']))
                        @foreach ($errores['contrasena'] as $contrasena)
                            <div class="text-danger">{{ $contrasena }}</div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="rol" class="required">Rol</label>
                    <select id="rol" name="admin" class="form-control">
                        <option value="0">Operario</option>
                        <option value="1">Administrador</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary mg">Agregar Operario</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

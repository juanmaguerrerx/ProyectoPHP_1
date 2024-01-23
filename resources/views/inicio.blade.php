<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('../images/fondo-pared-ladrillo-rustico_53876-92955.avif');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .bd {
            background-color: rgba(218, 218, 218, 0.8)
        }

        .in {
            background-color: rgba(255, 255, 255, 0.5)
        }
    </style>
</head>

<body>

    {{-- @include('navbar') --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bd">
                    <div class="card-header">
                        <h3 class="text-center">BungleBuilding S.L</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" name="email" class="form-control in" id="email"
                                    placeholder="Ingrese su correo electrónico"
                                    value="{{ isset($datosFormulario['email']) ? $datosFormulario['email'] : '' }}">
                                @if (isset($errores['email']))
                                    <div class="text-danger">{{ $errores['email'] }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña:</label>
                                <input type="password" name="contrasena" class="form-control in" id="password"
                                    value=" {{ isset($datosFormulario['contrasena']) ? $datosFormulario['contrasena'] : '' }}"
                                    placeholder="Ingrese su contraseña">

                                @if (isset($errores['contrasena']))
                                    <div class="text-danger">{{ $errores['contrasena'] }}</div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

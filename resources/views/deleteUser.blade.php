<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Operario | BungleBuilding S.L</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: grey;
            color: white;
        }

        .mg {
            margin-bottom: 5vh;
        }

        .pad {
            padding: 5vh;
        }

        .aste {
            color: red;
        }

        /* Estilos adicionales para centrar y decorar el contenedor */
        .delete-container {
            max-width: 400px;
            margin: 0 auto;
            border: 2px solid white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* Estilos para separar los campos */
        .mb-3 {
            margin-bottom: 15px;
        }

        /* Estilo para centrar el botón */
        .center-button {
            text-align: center;
        }
    </style>
</head>

<body>
    {{-- incluir el navbar --}}
    @include('navbar')
    <div class="container mt-5 pad">
        <h2 class="tp text-center">Eliminar Usuario</h2>

        <p class="text-center">¿Estás seguro de eliminar el operario? <br> Se eliminarán todas sus tareas correspondientes</p>

        <form method='POST' action="">
            @csrf
            <div class="delete-container">
                <input type="hidden" name="id" value="{{ $datosFormulario['id'] }}">

                <div class="mb-3">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" value="{{ $datosFormulario['nombre'] }}"
                        readonly>
                </div>

                <div class="mb-3">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" class="form-control" id="apellidos"
                        value="{{ $datosFormulario['apellidos'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="text" class="form-control" id="correo" value="{{ $datosFormulario['correo'] }}"
                        readonly>
                </div>

                <div class="mb-3">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" class="form-control" id="contrasena" value="********" readonly>
                </div>
                @php
                    if ($datosFormulario['admin']) {
                        $rol = 'Admin';
                    } else {
                        $rol = 'Operario';
                    }
                @endphp
                <div class="mb-3">
                    <label for="rol">Rol:</label>
                    <input type="text" class="form-control" id="rol" value="{{ $rol }}" readonly>
                </div>
            </div>

            <div class="center-button">
                <button type="submit" name="submit" class="btn btn-danger mg">Eliminar Operario</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

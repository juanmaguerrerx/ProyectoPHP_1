<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Tarea | BungleBuilding S.L</title>
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
        <h2 class="text-center">Ver Tarea</h2>
        <div class="delete-container">
            <input type="hidden" name="id" value="{{ $datosFormulario['id'] }}">

            <div class="mb-3">
                <label for="nif">NIF Cliente</label>
                <input type="text" class="form-control-md" id="nombre"
                    value="{{ $datosFormulario['nif_cliente'] }}" readonly>

                <label for="">Nombre Cliente</label>
                <input type="text" class="form-control-md" id="apellidos"
                    value="{{ $datosFormulario['nombre_cliente'] }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Telefono Cliente</label>
                <input type="text" class="form-control-md" id="correo"
                    value="{{ $datosFormulario['telefono_cliente'] }}" readonly>
            </div>
            <div class="mb-3">
                <label for="">Correo Cliente</label>
                <input type="text" class="form-control" id="correo"
                    value="{{ $datosFormulario['correo_cliente'] }}" readonly>
            </div>
            <div class="mb-3">
                <label for="">Descripción</label><br>
                <textarea name="descripcion" id="descripcion" cols="30" rows="6" readonly>{{ $datosFormulario['descripcion'] }}</textarea>
            </div>
            <div class="mb-3">
                <label for="">Anotaciones Anteriores</label><br>
                <textarea name="anotaciones_anteriores" id="anotaciones_anteriores" cols="100" rows="10" readonly>{{ $datosFormulario['anotaciones_anteriores'] }}</textarea>
            </div>
            @if (isset($datosFormulario['anotaciones_posteriores']))
                <div class="mb-3">
                    <label for="">Anotaciones Posteriores</label><br>
                    <textarea name="anotaciones_posteriores" id="anotaciones_posteriores" cols="100" rows="10" readonly>{{ $datosFormulario['anotaciones_posteriores'] }}</textarea>
                </div>
            @endif

            <div class="mb-3">
                <label for="">Provincia</label>
                <input type="text" class="form-control-sm" id="rol" value="{{ $datosFormulario['provincia'] }}"
                    readonly>

                <label for="">Estado</label>
                @php
                    switch ($datosFormulario['estado']) {
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
                <input type="text" class="form-control-sm" id="estado" value="{{ $st }}" readonly>
            </div>

            <div class="mb-3">
                <label for="">Fecha de creación</label>
                <input type="datetime" class="form-control-sm" id="rol"
                    value="{{ $datosFormulario['fecha_creacion'] }}" readonly>
                <label for="">Operario</label>
                <input type="text" class="form-control-sm" id="rol" value="{{ $datosFormulario['operario'] }}"
                    readonly>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

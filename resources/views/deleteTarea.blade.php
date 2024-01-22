<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Tarea | BungleBuilding S.L</title>
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
        <h2 class="tp text-center">Eliminar Tarea</h2>

        <p class="text-center">¿Estás seguro de eliminar la tarea?</p>

        <form method='POST' action="">
            @csrf
            <div class="delete-container">
                <input type="hidden" name="id" value="{{ $datosFormulario['id'] }}">

                <div class="mb-3">
                    <label for="nif">NIF Cliente</label>
                    <input type="text" class="form-control" id="nombre"
                        value="{{ $datosFormulario['nif_cliente'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="">Nombre Cliente</label>
                    <input type="text" class="form-control" id="apellidos"
                        value="{{ $datosFormulario['nombre_cliente'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="">Telefono Cliente</label>
                    <input type="text" class="form-control" id="correo"
                        value="{{ $datosFormulario['telefono_cliente'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="">Correo Cliente</label>
                    <input type="text" class="form-control" id="correo"
                        value="{{ $datosFormulario['correo_cliente'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="">Descripción</label>
                    <input type="text" class="form-control" id="rol"
                        value="{{ $datosFormulario['descripcion'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="">Provincia</label>
                    <input type="text" class="form-control" id="rol"
                        value="{{ $datosFormulario['provincia'] }}" readonly>
                </div>

                <div class="mb-3">
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
                    <input type="text" class="form-control" id="estado" value="{{ $st }}"
                        readonly>
                </div>

                <div class="mb-3">
                    <label for="">Fecha de creación</label>
                    <input type="datetime" class="form-control" id="rol" value="{{ $datosFormulario['fecha_creacion'] }}"
                        readonly>
                </div>

                <div class="mb-3">
                    <label for="">Operario</label>
                    <input type="text" class="form-control" id="rol" value="{{ $datosFormulario['operario'] }}"
                        readonly>
                </div>
            </div>

            <div class="center-button">
                <button type="submit" name="submit" class="btn btn-danger mg">Eliminar Tarea</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

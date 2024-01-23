<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Tarea | BungleBuilding S.L</title>
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
        <h2 class="tp">Modificar Tarea</h2>
        <p>&#40; <span class="aste">&#42;</span> son campos obligatorios&#41;</p>

        <form method='POST' action="">
            {{-- @csrf --}}
            <fieldset style="border: 1px solid white">
                <div class="form-row">

                    <legend>Datos cliente:</legend>
                    <div class="form-group col-md-6">
                        <label for="nif" class="required">NIF Cliente</label>
                        <input type="text" name="nif_cliente" class="form-control"
                            value="{{ $datosFormulario['nif_cliente'] }}" id="nif" placeholder="NIF o CIF">
                        @if (isset($errores['nif_cliente']))
                            <div class="text-danger">{{ $errores['nif_cliente'] }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cliente" class="required">Cliente</label>
                        <input type="text" name="nombre_cliente" class="form-control" id="Cliente"
                            value="{{ $datosFormulario['nombre_cliente'] }}" placeholder="Nombre y Apellidos">
                        @if (isset($errores['nombre_cliente']))
                            <div class="text-danger">{{ $errores['nombre_cliente'] }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="telefono" class="required">Teléfono de Contacto</label>
                        <input type="tel" name="telefono_cliente" class="form-control" id="telefono"
                            value="{{ $datosFormulario['telefono_cliente'] }}" placeholder="Teléfono">
                        @if (isset($errores['telefono_cliente']))
                            <div class="text-danger">{{ $errores['telefono_cliente'] }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email" class="required">Correo Electrónico</label>
                        <input type="email" name="correo_cliente" class="form-control" id="email"
                            value="{{ $datosFormulario['correo_cliente'] }}" placeholder="Correo Electrónico">
                        @if (isset($errores['correo_cliente']))
                            <div class="text-danger">{{ $errores['correo_cliente'] }}</div>
                        @endif
                    </div>
                </div>
            </fieldset>
            <div class="form-group">
                <label for="estado" class="required">Estado</label>
                <select name="estado" id="estado">
                    <option value="P" @if ($datosFormulario['estado'] == 'P') selected @endif>En proceso</option>
                    <option value="C" @if ($datosFormulario['estado'] == 'C') selected @endif>Cancelada</option>
                    <option value="R" @if ($datosFormulario['estado'] == 'R') selected @endif>Realizada</option>
                    <option value="B" @if ($datosFormulario['estado'] == 'B') selected @endif>Esperando aprobacion
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="descripcion" class="required">Descripción</label>
                <textarea class="form-control" name="descripcion" id="descripcion" rows="3">{{ $datosFormulario['descripcion'] }}</textarea>
                @if (isset($errores['descripcion']))
                    <div class="text-danger">{{ $errores['descripcion'] }}</div>
                @endif
            </div>
            <div class="form-row">

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="provincia" class="required">Provincia</label>
                        <select id="provincia" name="provincia" class="form-control">
                            @foreach ($provincias as $provincia)
                                <option value="{{ $provincia['cod'] }}"
                                    @if ($datosFormulario['provincia'] == $provincia['nombre']) selected @endif>{{ $provincia['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="codigoPostal" class="required">Código Postal</label>
                    <input type="text" name="codigo_postal" class="form-control"
                        value="{{ $datosFormulario['codigo_postal'] }}" id="codigoPostal" placeholder="Código Postal">
                    @if (isset($errores['codigo_postal']))
                        <div class="text-danger">{{ $errores['codigo_postal'] }}</div>
                    @endif
                </div>


                <div class="form-group col-md-6">
                    <label for="operario" class="required">Operario Encargado</label>
                    <select id="operario" name="operario" class="form-control form-control-md">
                        @foreach ($operarios as $operario)
                            <option value="{{ $operario['id'] }}" @if ($datosFormulario['operario'] == $operario['nombre'] . ' ' . $operario['apellidos']) selected @endif>
                                {{ $operario['nombre'] }}
                                {{ $operario['apellidos'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="anotaciones_anteriores">Anotaciones Anteriores</label>
                <textarea class="form-control" name="anotaciones_anteriores" id="anotacionesAnteriores" rows="3">{{ $datosFormulario['anotaciones_anteriores'] }}</textarea>
            </div>

            <div class="form-group">
                <label for="anotacionesAnteriores">Anotaciones Posteriores</label>
                <textarea class="form-control" name="anotaciones_posteriores" id="anotacionesAnteriores" rows="3">{{ $datosFormulario['anotaciones_posteriores'] }}</textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary mg">Modificar Tarea</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

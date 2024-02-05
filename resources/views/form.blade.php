<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Tarea | BungleBuilding S.L</title>
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
        <h2 class="tp">Añadir Tarea</h2>
        <p>&#40; <span class="aste">&#42;</span> son campos obligatorios&#41;</p>

        <form method='POST' action="{{ route('enviarForm') }}">
            @csrf
            <fieldset style="border: 1px solid white">
                <div class="form-row">

                    <legend>Datos cliente:</legend>
                    <div class="form-group col-md-6">
                        <label for="nif" class="required">NIF Cliente</label>
                        <input type="text" name="nif" class="form-control"
                            value="{{ isset($datosFormulario['nif']) ? $datosFormulario['nif'] : '' }}" id="nif"
                            placeholder="NIF o CIF">
                        @if (isset($errores['nif']))
                            <div class="text-danger">{{ $errores['nif'] }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cliente" class="required">Cliente</label>
                        <input type="text" name="cliente" class="form-control" id="Cliente"
                            value="{{ isset($datosFormulario['cliente']) ? $datosFormulario['cliente'] : '' }}"
                            placeholder="Nombre y Apellidos">
                        @if (isset($errores['cliente']))
                            <div class="text-danger">{{ $errores['cliente'] }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="telefono" class="required">Teléfono de Contacto</label>
                        <input type="tel" name="telefono" class="form-control" id="telefono"
                            value="{{ isset($datosFormulario['telefono']) ? $datosFormulario['telefono'] : '' }}"
                            placeholder="Teléfono">
                        @if (isset($errores['telefono']))
                            <div class="text-danger">{{ $errores['telefono'] }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email" class="required">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" id="email"
                            value="{{ isset($datosFormulario['email']) ? $datosFormulario['email'] : '' }}"
                            placeholder="Correo Electrónico">
                        @if (isset($errores['correo']))
                            <div class="text-danger">{{ $errores['correo'] }}</div>
                        @endif
                    </div>
                </div>
            </fieldset>
            <div class="form-group">
                <label for="descripcion" class="required">Descripción</label>
                <textarea class="form-control" name="descripcion" id="descripcion" rows="3">{{ isset($datosFormulario['descripcion']) ? $datosFormulario['descripcion'] : '' }}</textarea>
                @if (isset($errores['descripcion']))
                    <div class="text-danger">{{ $errores['descripcion'] }}</div>
                @endif
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="direccion" class="required">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                        value="{{ isset($datosFormulario['direccion']) ? $datosFormulario['direccion'] : '' }}"
                        id="direccion" placeholder="Dirección">
                    @if (isset($errores['direccion']))
                        <div class="text-danger">{{ $errores['direccion'] }}</div>
                    @endif
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="provincia" class="required">Provincia</label>
                        <select id="provincia" name="provincia" class="form-control">
                            @foreach ($provincias as $provincia)
                                <option value="{{ $provincia['cod'] }}">{{ $provincia['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="poblacion" class="required">Población</label>
                    <input type="text" name="poblacion"
                        value="{{ isset($datosFormulario['poblacion']) ? $datosFormulario['poblacion'] : '' }}"
                        class="form-control" id="poblacion" placeholder="Población">
                    @if (isset($errores['poblacion']))
                        <div class="text-danger">{{ $errores['poblacion'] }}</div>
                    @endif
                </div>
                <div class="form-group col-md-3">
                    <label for="codigoPostal" class="required">Código Postal</label>
                    <input type="text" name="codigoPostal" class="form-control"
                        value="{{ isset($datosFormulario['codigoPostal']) ? $datosFormulario['codigoPostal'] : '' }}"
                        id="codigoPostal" placeholder="Código Postal">
                    @if (isset($errores['codigoPostal']))
                        <div class="text-danger">{{ $errores['codigoPostal'] }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="operario" class="required">Operario Encargado</label>
                    <select id="operario" name="operario" class="form-control form-control-md">
                        @foreach ($operarios as $operario)
                            @if ($operario['admin'] == 0)
                                <option value="{{ $operario['id'] }}">{{ $operario['nombre'] }}
                                    {{ $operario['apellidos'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="anotacionesAnteriores">Anotaciones Anteriores</label>
                <textarea class="form-control" name="anotacionesAnteriores" id="anotacionesAnteriores" rows="3">{{ isset($datosFormulario['anotacionesAnteriores']) ? $datosFormulario['anotacionesAnteriores'] : '' }}</textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary mg">Agregar Tarea</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

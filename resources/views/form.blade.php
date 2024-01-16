<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Tareas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background-image: url(../images/fondo-pared-ladrillo-rustico_53876-92955.avif);
            background-repeat: no-repeat;
            background-size: cover;
        }
        *{
            color: white;
        }
        select>option{
            color: black;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Formulario de Tareas</h2>
    <form method='POST' action="{{route(crearTarea)}}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nif">NIF o CIF</label>
                <input type="text" class="form-control" id="nif" placeholder="NIF o CIF" required>
            </div>
            <div class="form-group col-md-6">
                <label for="personaContacto">Persona de Contacto</label>
                <input type="text" class="form-control" id="personaContacto" placeholder="Nombre y Apellidos" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="telefono">Teléfono de Contacto</label>
                <input type="tel" class="form-control" id="telefono" placeholder="Teléfono" required>
            </div>
            <div class="form-group col-md-6">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" placeholder="Correo Electrónico" required>
            </div>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" rows="3" required></textarea>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" placeholder="Dirección" required>
            </div>
            <div class="form-group col-md-3">
                <label for="poblacion">Población</label>
                <input type="text" class="form-control" id="poblacion" placeholder="Población" required>
            </div>
            <div class="form-group col-md-3">
                <label for="codigoPostal">Código Postal</label>
                <input type="text" class="form-control" id="codigoPostal" placeholder="Código Postal" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="provincia">Provincia</label>
                <select id="provincia" class="form-control" required>
                    <option value="">Seleccionar...</option>
                    <!-- Agrega las provincias con la bbdd-->
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado" placeholder="Estado" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="operario">Operario Encargado</label>
                <select id="operario" class="form-control" required>
                    <option value="">Seleccionar...</option>
                    <!-- Agrega los operarios que necesites -->
                    
                    <!-- ... -->
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="fechaRealizacion">Fecha de Realización</label>
                <input type="date" class="form-control" id="fechaRealizacion" required>
            </div>
        </div>
        <div class="form-group">
            <label for="anotacionesAnteriores">Anotaciones Anteriores</label>
            <textarea class="form-control" id="anotacionesAnteriores" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="anotacionesPosteriores">Anotaciones Posteriores</label>
            <textarea class="form-control" id="anotacionesPosteriores" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Tarea</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Tareas</title>
    <style>
        .marg{
            margin-top: 10vh;
        }
    </style>
    <!-- Enlace a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
@include('navbar')
<div class="container-fluid marg">
    <h3 class="text-center">Lista de Tareas</h3>

    <div class="container-lg mt-5 izq">
        <!-- Tabla Bootstrap -->
        <table class="table table-bordered mx-auto">
            <!-- En cabezado de la tabla -->
            <thead>
                <tr>
                    <th>NIF/CIF Cliente</th>
                    <th>Persona Contacto Nombre</th>
                    <th>Teléfono Contacto</th>
                    <th>Descripción</th>
                    <th>Correo Electrónico</th>
                    <th>Código Postal</th>
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
            <!-- Cuerpo de la tabla (vacío en este caso) -->
            <tbody>
                <!-- Puedes agregar más filas según sea necesario -->
                
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

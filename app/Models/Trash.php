<?php
// Si el filtro de estado no es nulo
        if ($f != NULL) {
            // Si es admin
            if ($esAdmin) {
                // Si filtro de nombre no es nulo
                if ($n != null) {
                    // Muestra todas las tareas segun el filtro de estado, de nombre y orden de fecha
                    $stmt = $conexion->prepare("SELECT * FROM tareas " . "WHERE estado = '$f' AND operario_id = '$n' $oF");
                } else {
                    // Si filtro de nombre es nulo
                    // Muestra todas las tareas segun el estado y con orden de fecha
                    $stmt = $conexion->prepare("SELECT * FROM tareas " . "WHERE estado = '$f' $oF");
                }

                // Si no es admin
            } else {
                // Muestra todas las tareas del operario que esta usando la aplicacion segun filtro de estado y fecha

                $stmt = $conexion->prepare("SELECT * FROM tareas WHERE operario_id = $operarioId AND estado = '$f' $oF");

                // No se pone filtro de nombre porque el operario no puede ver mas tareas aparte de las suyas
            }
            // Si el filtro de estado es nulo
        } else {
            // Si es admin
            if ($esAdmin) {
                // Si filtro de nombre es nulo
                if ($n != null) {
                    // Muestra todas las tareas segun nombre y orden de fecha
                    $stmt = $conexion->prepare("SELECT * FROM tareas " . "WHERE operario_id = '$n' $oF");

                    // Si filtro de nombre no es nulo
                } else {
                    // Muesta todas las tareas segun orden de fecha
                    $stmt = $conexion->prepare("SELECT * FROM tareas $oF");
                }
                // Si no es admin
            } else {
                // Muestra todas las tareas del operario cliente segun orden de fech
                $stmt = $conexion->prepare("SELECT * FROM tareas WHERE operario_id = $operarioId $oF");
            }
        }
        dd($stmt);











        public function getTareas($operarioId, string $f = null, string $n = null, string $oF = null): array
        {
            $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
            $tareas = array();
            $opMod = new Operarios;
        
            // Verificar si el operario que está en la página es admin
            $esAdmin = $opMod->esAdmin($operarioId);
        
            // Construir la parte de la consulta relacionada con el orden
            $orden = ($oF == 'fC') ? "ORDER BY fecha_creacion" : (($oF == 'fR') ? "ORDER BY fecha_realizacion IS NULL, fecha_realizacion" : "ORDER BY id");
        
            // Construir la parte de la consulta relacionada con el filtro de nombre
            $filtroNombre = $n ? " AND operario_id = '$n'" : null;
        
            // Construir la parte de la consulta relacionada con el filtro de estado
            $filtroEstado = $f ? " AND estado = '$f'" : null;
        
            // Construir la consulta principal
            $consulta = "SELECT * FROM tareas WHERE";
        
            // Agregar la condición de operario_id
            $consulta .= $esAdmin ? "" : " operario_id = '$operarioId'";
        
            // Agregar las condiciones de filtro
            $consulta .= $filtroEstado . $filtroNombre;
        
            // Agregar la parte de orden
            $consulta .= " $orden";
        
            // Preparar y ejecutar la consulta
            $stmt = $conexion->prepare($consulta);
            $stmt->execute();
        
            // Obtener las tareas como un array asociativo
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Construir un array final recorriendo el array de resultados
            foreach ($resultados as $fila) {
                // ... (resto del código de construcción del array de tareas)
            }
        
            return $tareas;
        }
        
<?php

use App\Models\ConexionDB;

class TareaModelo
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    //Funcion para crear Tareas
    public function crearTarea(
        $nifCifCliente,
        $personaContactoNombre,
        $personaContactoApellidos,
        $telefonoContacto,
        $descripcion,
        $correoElectronico,
        $direccion,
        $poblacion,
        $codigoPostal,
        $provincia,
        $estado,
        $fechaCreacion,
        $operarioEncargado,
        $fechaRealizacion,
        $anotacionesAnteriores,
        $anotacionesPosteriores
    ) {
        try {
            $stmt = $this->conexion->prepare("
                INSERT INTO tareas (
                    nif_cif_cliente,
                    persona_contacto_nombre,
                    persona_contacto_apellidos,
                    telefono_contacto,
                    descripcion,
                    correo_electronico,
                    direccion,
                    poblacion,
                    codigo_postal,
                    provincia,
                    estado,
                    fecha_creacion,
                    operario_encargado,
                    fecha_realizacion,
                    anotaciones_anteriores,
                    anotaciones_posteriores
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bindParam(1, $nifCifCliente);
            $stmt->bindParam(2, $personaContactoNombre);
            $stmt->bindParam(3, $personaContactoApellidos);
            $stmt->bindParam(4, $telefonoContacto);
            $stmt->bindParam(5, $descripcion);
            $stmt->bindParam(6, $correoElectronico);
            $stmt->bindParam(7, $direccion);
            $stmt->bindParam(8, $poblacion);
            $stmt->bindParam(9, $codigoPostal);
            $stmt->bindParam(10, $provincia);
            $stmt->bindParam(11, $estado);
            $stmt->bindParam(12, $fechaCreacion);
            $stmt->bindParam(13, $operarioEncargado);
            $stmt->bindParam(14, $fechaRealizacion);
            $stmt->bindParam(15, $anotacionesAnteriores);
            $stmt->bindParam(16, $anotacionesPosteriores);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {

            return false;
        }
    }

    //Funcion para modificar la tarea
    public function modificarTarea()
    {
        try {

            return true;
        } catch (PDOException $e) {

            return false;
        }
    }

    //Funcion para eliminar la tarea
    public function eliminarTarea()
    {
        try {

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTareas($admin){
        try {
            $tareas = array(
                '' => '',
                '' => '',
            );
            $stmt = $this->conexion->prepare("SELECT  FROM tareas WHERE");

            $stmt->execute();

            // insertas
            
            return $tareas;
        } catch (PDOException $e) {

            return 'error';
        }
    }
}














// Ejemplo de uso
// $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
// $tareaModelo = new TareaModelo($conexion);

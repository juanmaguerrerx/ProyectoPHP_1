<?php

namespace App\Models;

use App\Models\ConexionDB;
use PDO;
use PDOException;
use PhpParser\Node\Stmt;

class Tareas
{

    //Funcion para crear Tareas
    public function crearTarea($datos)
    {
        try {
            $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
            $stmt = $conexion->prepare("
                INSERT INTO tareas (
                nif_cliente,	
                nombre_cliente,
                telefono_cliente,	
                correo_cliente,	
                descripcion,	
                poblacion,	
                codigo_postal,	
                provincia,			
                operario_id,	
                anotaciones_anteriores	
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

            $stmt->bindParam(1, $datos['nif']);
            $stmt->bindParam(2, $datos['cliente']);
            $stmt->bindParam(3, $datos['telefono']);
            $stmt->bindParam(4, $datos['email']);
            $stmt->bindParam(5, $datos['descripcion']);
            $stmt->bindParam(6, $datos['poblacion']);
            $stmt->bindParam(7, $datos['codigoPostal']);
            $stmt->bindParam(8, $datos['provincia']);
            $stmt->bindParam(9, $datos['operario']);
            $stmt->bindParam(10, $datos['anotacionesAnteriores']);

            // dd($stmt);
            echo "Consulta SQL: " . $stmt->queryString . PHP_EOL;

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            dd($e->getMessage());
        }
    }


    //Funcion para eliminar la tarea
    public function deleteTarea($id)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");

        $stmt->bindParam(1, $id);

        $stmt->execute();

        return true;
    }

    //Funcion que obtiene las tareas asociadas a la id del operario pasado como parámetro
    public function getTareas($operarioId, $f = null, $n = null, $oF = null, $a_d = null)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $tareas = array();
        $opMod = new Operarios;
        // Verificar si el operario es administrador
        $esAdmin = $opMod->esAdmin($operarioId);
        // dd($oF);

        if ($oF != null) {
            if ($oF == 'fC') {
                $oF = "ORDER BY fecha_creacion ";
            } else if ($oF == 'fR') {
                $oF = "ORDER BY fecha_realizacion , fecha_realizacion IS NULL";
            }
        } else $oF = 'ORDER BY id';


        // dd($oF);
        if ($f != NULL) {
            // Construir la consulta SQL en función de si es admin o no

            if ($esAdmin) {
                if ($n != null) {
                    $stmt = $conexion->prepare("SELECT * FROM tareas " . "WHERE estado = '$f' AND operario_id = '$n' $oF");
                } else {
                    $stmt = $conexion->prepare("SELECT * FROM tareas " . "WHERE estado = '$f' $oF");
                }
            } else {
                $stmt = $conexion->prepare("SELECT * FROM tareas WHERE operario_id = $operarioId AND estado = '$f' $oF");
            }
        } else {
            if ($esAdmin) {
                if ($n != null) {
                    $stmt = $conexion->prepare("SELECT * FROM tareas " . "WHERE operario_id = '$n' $oF");
                } else {
                    $stmt = $conexion->prepare("SELECT * FROM tareas $oF");
                }
            } else {
                $stmt = $conexion->prepare("SELECT * FROM tareas WHERE operario_id = $operarioId $oF");
            }
        }
        // dd($stmt);
        $stmt->execute();

        // Obtener todas las tareas como un array asociativo
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // Iterar sobre los resultados y construir el array final
        foreach ($resultados as $fila) {
            $opMod = new Operarios;
            $provMod = new Provincias;
            $tarea = array(
                'id' => $fila['id'],
                'nif_cliente' => $fila['nif_cliente'],
                'nombre_cliente' => $fila['nombre_cliente'],
                'telefono_cliente' => $fila['telefono_cliente'],
                'correo_cliente' => $fila['correo_cliente'],
                'descripcion' => $fila['descripcion'],
                'codigo_postal' => $fila['codigo_postal'],
                'provincia' => $provMod->getProv($fila['provincia']),
                'estado' => $fila['estado'],
                'fecha_creacion' => $fila['fecha_creacion'],
                'operario' => $opMod->getNombre($fila['operario_id']),
                'fecha_realizacion' => $fila['fecha_realizacion'],
                'anotaciones_anteriores' => $fila['anotaciones_anteriores'],
                'anotaciones_posteriores' => $fila['anotaciones_posteriores'],
            );
            $tareas[] = $tarea;
        }
        return $tareas;
    }



    public function getTarea($id)
    {
        // dd($id);
            $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
            $stmt = $conexion->prepare("SELECT * FROM tareas WHERE id = $id");

            $stmt->execute();

            // Obtener todos los resultados como un array asociativo
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $provMod = new Provincias;
            $opMod = new Operarios;

            // Iterar sobre los resultados y construir el array final
            foreach ($resultados as $fila) {
                $tarea = array(
                    'id' => $fila['id'],
                    'nif_cliente' => $fila['nif_cliente'],
                    'nombre_cliente' => $fila['nombre_cliente'],
                    'telefono_cliente' => $fila['telefono_cliente'],
                    'correo_cliente' => $fila['correo_cliente'],
                    'descripcion' => $fila['descripcion'],
                    'codigo_postal' => $fila['codigo_postal'],
                    'provincia' => $provMod->getProv($fila['provincia']),
                    'estado' => $fila['estado'],
                    'fecha_creacion' => $fila['fecha_creacion'],
                    'operario' => $opMod->getNombre($fila['operario_id']),
                    'fecha_realizacion' => $fila['fecha_realizacion'],
                    'anotaciones_anteriores' => $fila['anotaciones_anteriores'],
                    'anotaciones_posteriores' => $fila['anotaciones_posteriores'],
                );
            }
            // dd($tarea);
            return $tarea;
    }

    //Funcion para modificar la tarea
    public function modTarea($idTarea, $datos, $fecha_realizacion)
    {
            $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

            $consulta = "
            UPDATE tareas SET 
            nif_cliente = ?,
            nombre_cliente = ?,
            telefono_cliente = ?,
            correo_cliente = ?,
            descripcion = ?,
            codigo_postal = ?,
            provincia = ?,
            estado = ?,
            operario_id = ?,
            fecha_realizacion = ?,
            anotaciones_anteriores = ?,
            anotaciones_posteriores = ?
            WHERE id = ?
        ";

            $stmt = $conexion->prepare($consulta);

            $stmt->bindParam(1, $datos['nif_cliente']);
            $stmt->bindParam(2, $datos['nombre_cliente']);
            $stmt->bindParam(3, $datos['telefono_cliente']);
            $stmt->bindParam(4, $datos['correo_cliente']);
            $stmt->bindParam(5, $datos['descripcion']);
            $stmt->bindParam(6, $datos['codigo_postal']);
            $stmt->bindParam(7, $datos['provincia']);
            $stmt->bindParam(8, $datos['estado']);
            $stmt->bindParam(9, $datos['operario']);
            $stmt->bindParam(10, $fecha_realizacion);
            $stmt->bindParam(11, $datos['anotaciones_anteriores']);
            $stmt->bindParam(12, $datos['anotaciones_posteriores']);
            $stmt->bindParam(13, $datos['id']);
            // dd($stmt);
            // dd($datos);
            $stmt->execute();

            return true;
            // Manejar la excepción según tus necesidades

    }

    public function getTareasPag($t, $p, $g)
    {
        $inicio = ($p - 1) * $g;
        $tP = array_slice($t, $inicio, $g);
        return $tP;
    }
}

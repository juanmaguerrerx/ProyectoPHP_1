<?php

/**
 * DOCUMENTACION
 */

namespace App\Models;

use App\Models\ConexionDB;
use DateTime;
use PDO;
use PDOException;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Cast\Bool_;
use PhpParser\Node\Stmt;

use function PHPUnit\Framework\isEmpty;

/**
 * 
 */
class Tareas
{

    /**
     * Funcion para crear una Tarea con los datos proporcionados
     * como parámetro
     *
     * @param array $datos
     * @return Bool
     */
    public function crearTarea(array $datos): Bool
    {
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
        // echo "Consulta SQL: " . $stmt->queryString . PHP_EOL;

        $stmt->execute();
        return true;
    }


    /**
     * Funcion que elimina la tarea correspondiente al id pasado
     * como parámetro
     *
     * @param integer $id
     * @return Bool
     */
    public function deleteTarea(int $id): Bool
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");

        $stmt->bindParam(1, $id);

        $stmt->execute();

        return true;
    }

    /**
     * Funcion para obtener una lista de Tareas
     *
     * @param integer $operarioId -> id del operario que está usando la pagina
     * @param string|null $f -> filtro de estado
     * @param string|null $n -> filtro de nombre de operario
     * @param string|null $oF -> el orden de la fecha
     * @return array
     */
    public function getTareas(int $operarioId, string $f = null, string $n = null, string $oF = null): array
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $tareas = array();
        $opMod = new Operarios;

        // Verificar si el operario que está en la página es admin
        $esAdmin = $opMod->esAdmin($operarioId);

        // Si el filtro de orden de fecha no es nulo
        if ($oF != null) {
            // Si es fC ordenar por fecha_creacion
            if ($oF == 'fC') {
                $oF = "ORDER BY fecha_creacion ";
                // Si es fR por fecha_realizacion
            } else if ($oF == 'fR') {
                $oF = "ORDER BY fecha_realizacion IS NULL, fecha_realizacion";
            }
            // Y si es nulo ordenar por id
        } else $oF = 'ORDER BY id';

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
        // dd($stmt);
        $stmt->execute();

        // Obtener las tareas como un array asociativo
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // Construir un array final recorriendo el array de resultados
        foreach ($resultados as $fila) {
            $opMod = new Operarios;
            $provMod = new Provincias;


            if ($fila['fecha_realizacion'] !== null) {
                $fechaObj = new DateTime($fila['fecha_realizacion']);
                $fila['fecha_realizacion'] = $fechaObj->format('Y-m-d');
            }else {
                $fila['fecha_realizacion']=='';
            }
            $fechaObj = new DateTime($fila['fecha_creacion']);
            $fila['fecha_creacion'] = $fechaObj->format('Y-m-d');

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


    /**
     * Funcion para obtener una sola tarea en funcion de su id
     *
     * @param integer $id -> id de la tarea
     * @return array -> tarea
     */
    public function getTarea(int $id): array
    {

        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        // SELECT de todos los datos de la tarea cuyo id sea el que pasamos de parámetro
        $stmt = $conexion->prepare("SELECT * FROM tareas WHERE id = ?");
        $stmt->bindParam(1, $id);

        $stmt->execute();

        // Obtener las tareas como un array asociativo
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $provMod = new Provincias;
        $opMod = new Operarios;

        

        // Construir un array final recorriendo el array de resultados
        foreach ($resultados as $fila) {

            if ($fila['fecha_realizacion'] !== null) {
                $fechaObj = new DateTime($fila['fecha_realizacion']);
                $fila['fecha_realizacion'] = $fechaObj->format('Y-m-d');
            }else {
                $fila['fecha_realizacion']=='';
            }
            $fechaObj = new DateTime($fila['fecha_creacion']);
            $fila['fecha_creacion'] = $fechaObj->format('Y-m-d');

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
        // Devolvemos la tarea
        return $tarea;
    }

    /**
     * Funcion que modifica la tarea
     *
     * @param integer $idTarea -> id de la tarea a modificar
     * @param string $datos -> datos a cambiar
     * @return Bool
     */
    public function modTarea(int $idTarea, array $datos): Bool
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

        if ($datos['fecha_realizacion'] !== null || !isEmpty($datos['fecha_realizacion']) || $datos['fecha_realizacion'] !== '0000-00-00 00:00:00') {
            $fecha = $datos['fecha_realizacion'];
        }else
        $fecha = 'NULL';

        // dd($fecha);

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
        $stmt->bindParam(10, $fecha);
        $stmt->bindParam(11, $datos['anotaciones_anteriores']);
        $stmt->bindParam(12, $datos['anotaciones_posteriores']);
        $stmt->bindParam(13, $datos['id']);

        // dd($stmt, $fecha);

        $stmt->execute();


        // Devuelve true al realizar
        return true;
    }

    public function getFechaCreacion(int $id)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT fecha_creacion FROM tareas WHERE id = ?");
        $stmt->bindParam(1, $id);

        $stmt->execute();

        // Obtener las tareas como un array asociativo
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['fecha_creacion'];
    }

    /**
     * Funcion para paginar tareas
     *
     * @param array $t -> tareas
     * @param integer $p -> la pagina en la que está
     * @param integer $g -> la agrupacion de tareas por página
     * @return array
     */
    public function getTareasPag(array $t, int $p, int $g): array
    {
        $inicio = ($p - 1) * $g;
        $tP = array_slice($t, $inicio, $g);
        return $tP;
    }
}

<?php

namespace App\Models;

use PDO;
use PDOException;

use App\Models\ConexionDB;
use PhpParser\Node\Stmt;

/**
 * Clase Operario
 */
class Operarios
{

    /**
     * Funcion que devuelve lista de operarios
     *
     * @param string|null $s -> Cadena de texto para hacer busqueda en la tabla
     * @param boolean|null $r -> Rol para filtrar datos en la tabla
     * @return array
     */
    public function getOperarios(string $s = null, $r = null): array
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $operarios = array();

        //Si hay busqueda
        if ($s != null) {
            $stmt = $conexion->prepare("SELECT * FROM operarios WHERE nombre LIKE '%$s%' OR id LIKE '%$s%' OR apellidos LIKE '%$s%' OR correo LIKE '%$s%' OR contrasena LIKE '%$s%'");

            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $operario = array(
                    'id' => $fila['id'],
                    'nombre' => $fila['nombre'],
                    'apellidos' => $fila['apellidos'],
                    'correo' => $fila['correo'],
                    'contrasena' => $fila['contrasena'],
                    'admin' => $fila['admin'],
                );
                $operarios[] = $operario;
            }

            return $operarios;

            // Si no hay busqueda
        } else {

            // Si hay rol a filtrar
            if ($r != null) {
                $t = "WHERE admin=$r";

                // Si no hay rol
            } else {
                $t = '';
            }
            // Hacemos el select con los parametros
            $stmt = $conexion->prepare("SELECT id, nombre, apellidos, correo, contrasena, admin FROM operarios $t");

            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $operario = array(
                    'id' => $fila['id'],
                    'nombre' => $fila['nombre'],
                    'apellidos' => $fila['apellidos'],
                    'correo' => $fila['correo'],
                    'contrasena' => $fila['contrasena'],
                    'admin' => $fila['admin'],
                );
                $operarios[] = $operario;
            }

            return $operarios;
        }
    }

    /**
     * Funcion para crear operario
     *
     * @param array $datos -> datos proporcionados
     * @return boolean -> si se ejecutó correctamente
     */
    public function crearOperario(array $datos): bool
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $stmt = $conexion->prepare("
                INSERT INTO operarios (
                nombre,	
                apellidos,
                correo,	
                contrasena,	
                admin
                ) VALUES (?, ?, ?, ?, ?)
                ");

        $stmt->bindParam(1, $datos['nombre']);
        $stmt->bindParam(2, $datos['apellidos']);
        $stmt->bindParam(3, $datos['correo']);
        $stmt->bindParam(4, $datos['contrasena']);
        $stmt->bindParam(5, $datos['admin']);

        $stmt->execute();
        return true;
    }


    /**
     * Funcion para saber si un operario es admin o no
     *
     * @param integer $id -> id del operario
     * @return bool -> si es admin o no
     */
    public function esAdmin($id)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT admin FROM operarios WHERE id = ?");

        $stmt->bindParam(1, $id);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($resultado['admin'])){
            $resultado = $resultado['admin'];
        }else $resultado = false;
        return $resultado;
    }

    /**
     * Funcion que obtiene el nombre completo de un operario
     *
     * @param integer $id -> id del operario
     * @return string -> nombre completo
     */
    public function getNombre(int $id): string
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT nombre, apellidos FROM operarios WHERE id = :id");

        $stmt->execute([':id' => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $nombreCompleto = $resultado['nombre'] . ' ' . $resultado['apellidos'];

        return $nombreCompleto;
    }

    /**
     * Funcion que obtiene el id de un operario
     *
     * @param string $email -> el email para el que queremos saber su id
     * @return integer -> id correspondiente
     */
    public function getId(string $email): int
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT id FROM operarios WHERE correo = ?");

        $stmt->bindParam(1, $email);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['id'];
    }

    /**
     * Obtiene email de un operario
     *
     * @param int $id
     * @return string
     */
    public function getEmail(int $id): string
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT correo FROM operarios WHERE id = ?");

        $stmt->bindParam(1, $id);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['correo'];
    }

    /**
     * Funcion para paginar la tabla de operarios
     *
     * @param array $o -> lista de operarios
     * @param integer $p -> pagina en la que se encuentra
     * @param integer $g -> grupo de elementos por pagina
     * @return array -> lista con los datos de la pagina actual
     */
    public function getOperariosPag(array $o, int $p, int $g): array
    {
        $inicio = ($p - 1) * $g;
        $oP = array_slice($o, $inicio, $g);
        return $oP;
    }

    /**
     * Funcion que dice si existe un operario
     *
     * @param string $c -> correo del operario a averiguar
     * @return boolean -> si existe o no
     */
    public function isExist(string $c): bool
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT EXISTS(SELECT 1 FROM operarios WHERE correo = '$c');
        ");
        // $stmt->bindParam(':correo',$c, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Funcion que modifica operarios
     *
     * @param integer $idOperario
     * @param array $datos
     * @return boolean
     */
    public function modOperario(int $idOperario, array $datos): bool
    {

        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $consulta = "
            UPDATE operarios SET
            nombre = ?,
            apellidos = ?,
            correo = ?,
            contrasena = ?,
            admin = ?
            WHERE id = ?
        ";

        $stmt = $conexion->prepare($consulta);

        $stmt->bindParam(1, $datos['nombre']);
        $stmt->bindParam(2, $datos['apellidos']);
        $stmt->bindParam(3, $datos['correo']);
        $stmt->bindParam(4, $datos['contrasena']);
        $stmt->bindParam(5, $datos['admin']);
        $stmt->bindParam(6, $idOperario);

        $stmt->execute();

        return true;
    }

    /**
     * Funcion que devuelve un operario
     *
     * @param integer $id -> id del operario a devolver
     * @return array
     */
    public function getOperario(int $id): array
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $operarios = array();
        $stmt = $conexion->prepare("SELECT id, nombre, apellidos, correo, contrasena, admin FROM operarios WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $fila) {
            $operario = array(
                'id' => $fila['id'],
                'nombre' => $fila['nombre'],
                'apellidos' => $fila['apellidos'],
                'correo' => $fila['correo'],
                'contrasena' => $fila['contrasena'],
                'admin' => $fila['admin'],
            );
        }
        return $operario;
    }

    /**
     * Funcion para borrar un operario
     *
     * @param integer $id -> id del operario a eliminar
     * @return boolean -> si lo ha borrado o no
     */
    public function deleteOperario(int $id): bool
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        // Para poder borrar el usuario antes tenemos que borrar las tareas que lo relacionan
        $stmt = $conexion->prepare("DELETE FROM tareas WHERE operario_id= ?");
        $stmt->bindParam(1,$id);
        $stmt->execute();


        //Una vez borrada sus tareas borramos el usuario
        $stmt = $conexion->prepare("DELETE FROM operarios WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return true;
    }

    /**
     * Funcion que devuelve la contraseña del operario
     *
     * @param string $email -> email del usuraio
     * @return string -> contraseña
     */
    public function getContrasena(string $email): string
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $consulta = "SELECT contrasena FROM operarios WHERE correo = :correo;";

        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':correo', $email);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            return $resultado['contrasena'];
        } else {
            return false;
        }
    }
}

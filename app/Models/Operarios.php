<?php

namespace App\Models;

use PDO;
use PDOException;

use App\Models\ConexionDB;
use PhpParser\Node\Stmt;

class Operarios
{

    public function getOperarios()
    {
            $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
            $operarios = array();
            $stmt = $conexion->prepare("SELECT id, nombre, apellidos, correo, contrasena, admin FROM operarios");

            $stmt->execute();

            // Obtener todos los resultados como un array asociativo
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Iterar sobre los resultados y construir el array final
            foreach ($resultados as $fila) {
                $operario = array(
                    'id' => $fila['id'],
                    'nombre' => $fila['nombre'],
                    'apellidos' => $fila['apellidos'],
                    'correo' => $fila['correo'],
                    'contrasena' => $fila['contrasena'],
                    'admin' => $fila['admin'],
                );
                // Agregar el operario al array principal
                $operarios[] = $operario;
            }

            return $operarios;
    
    }

    public function crearOperario($datos)
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

            // dd($stmt);
            // echo "Consulta SQL: " . $stmt->queryString . PHP_EOL;

            $stmt->execute();
            return true;
        
    }

    public function esAdmin($id)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT admin FROM operarios WHERE id = ?");

        $stmt->bindParam(1,$id);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['admin'];
    }

    public function getNombre($id)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT nombre, apellidos FROM operarios WHERE id = :id");

        // $stmt->bindParam(1,$id);
        $stmt->execute([':id'=>$id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $nombreCompleto = $resultado['nombre'] . ' ' . $resultado['apellidos'];

        return $nombreCompleto;
    }
    public function getId($email)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT id FROM operarios WHERE correo = ?");

        $stmt->bindParam(1, $email);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['id'];
    }

    public function getOperariosPag($o, $p, $g)
    {
        $inicio = ($p - 1) * $g;
        $oP = array_slice($o, $inicio, $g);
        return $oP;
    }

    public function isExist($c)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT EXISTS(SELECT 1 FROM operarios WHERE correo = '$c');
        ");
        // $stmt->bindParam(':correo',$c, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function modOperario($idOperario, $datos)
    {
        try {
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

            // dd($datos);
            // dd($idOperario);

            $stmt->execute();
            // dd($stmt);  

            return true;
        } catch (PDOException $e) {
            // Manejar la excepción según tus necesidades
            dd($e->getMessage());
        }
    }

    public function getOperario($id)
    {
        try {
            $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
            $operarios = array();
            $stmt = $conexion->prepare("SELECT id, nombre, apellidos, correo, contrasena, admin FROM operarios WHERE id = $id");

            $stmt->execute();

            // Obtener todos los resultados como un array asociativo
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Iterar sobre los resultados y construir el array final
            foreach ($resultados as $fila) {
                $operario = array(
                    'id' => $fila['id'],
                    'nombre' => $fila['nombre'],
                    'apellidos' => $fila['apellidos'],
                    'correo' => $fila['correo'],
                    'contrasena' => $fila['contrasena'],
                    'admin' => $fila['admin'],
                );
                // Agregar el operario al array principal
            }
            // dd($operario);
            return $operario;
        } catch (PDOException $e) {
            return 'error';
        }
    }

    public function deleteOperario($id)
    {
        try {
            $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

            $consulta = "DELETE FROM operarios WHERE id = ?";

            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(1, $id);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            dd($e->getMessage());
        }
    }

    public function getContrasena($email)
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
            return null; // o maneja el caso en que no se encontró ninguna contraseña
        }
    }
}

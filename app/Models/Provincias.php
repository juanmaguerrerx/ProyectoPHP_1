<?php

namespace App\Models;


use PDO;
use PDOException;
use App\Models\ConexionDB;

class Provincias
{

    public function getProvincias()
    {
        try {
            $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
            $provincias = array();
            $stmt = $conexion->prepare("SELECT cod, nombre FROM tbl_provincias");

            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $provincia = array(
                    'cod' => $fila['cod'],
                    'nombre' => $fila['nombre'],
                );
                $provincias[] = $provincia;
            }

            return $provincias;
        } catch (PDOException $e) {
            return 'error';
        }
    }

    public function getProv($cod)
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT nombre FROM tbl_provincias WHERE cod = $cod");

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['nombre'];

    }
}

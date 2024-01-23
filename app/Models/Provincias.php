<?php

namespace App\Models;


use PDO;
use PDOException;
use App\Models\ConexionDB;

/**
 * Clase Provincias
 */
class Provincias
{
    /**
     * Funcion que devuelve el listado de provincias
     *
     * @return array
     */
    public function getProvincias(): array
    {

        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();
        $provincias = array();

        //Seleccionamos codigo y nombre de la tabla provincias
        $stmt = $conexion->prepare("SELECT cod, nombre FROM tbl_provincias");

        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Recorremos el resultado para agregarlo a un array asociativo
        foreach ($resultados as $fila) {
            $provincia = array(
                'cod' => $fila['cod'],
                'nombre' => $fila['nombre'],
            );
            $provincias[] = $provincia;
        }

        return $provincias;
    }

    /**
     * Funcion que devuelve el nombre de la provincia correspondiente a su codigo
     *
     * @param int $cod -> codigo
     * @return string -> nombre de la provincia
     */
    public function getProv(int $cod): string
    {
        $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

        $stmt = $conexion->prepare("SELECT nombre FROM tbl_provincias WHERE cod = $cod");

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['nombre'];
    }
}

<?php

use App\Models\ConexionDB;

class Provincias {
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getProvincias(){
        try {
            $provincias = array(
                'cod' => '',
                'nombre' => '',
            );
            $stmt = $this->conexion->prepare("SELECT cod, nombre FROM tbl_provincias");

            $stmt->execute();
            return $provincias;
        } catch (PDOException $e) {

            return 'error';
        }
    }

}
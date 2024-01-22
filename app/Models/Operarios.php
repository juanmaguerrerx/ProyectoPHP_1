<?php

use App\Models\ConexionDB;

class Operarios {
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getOperarios(){
        try {
            $operarios = array(
                'id' => '',
                'nombre' => '',
                'apellidos' => '',
            );
            $stmt = $this->conexion->prepare("SELECT id, nombre, apellidos FROM operarios");


            $stmt->execute();

            //guardar los datos de stmt en operarios


            return $operarios;
        } catch (PDOException $e) {

            return 'error';
        }
    }

}
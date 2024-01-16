<?php

class ConexionDB {
    private static $instancia;
    private $conexion;
    private $host = 'localhost';
    private $usuario = 'root';
    private $contrasena = '';
    private $nombreBD = 'BungleBuilding';

    private function __construct() {
        try {
            $this->conexion = new PDO("mysql:host={$this->host};dbname={$this->nombreBD}", $this->usuario, $this->contrasena);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function obtenerInstancia() {
        if (!self::$instancia) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function obtenerConexion() {
        return $this->conexion;
    }

}


// $conexion = ConexionDB::obtenerInstancia()->obtenerConexion();

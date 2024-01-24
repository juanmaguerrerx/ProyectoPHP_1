<?php

namespace App\Models;

class SessionMan
{
    /**
     * Método para iniciar la sesión
     *
     * @return void
     */
    public function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Escribir en la sesión
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function write(string $key, string $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Leer la sesión
     *
     * @param string $key
     * @return mixed|null
     */
    public function read(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Verificar si existe la sesión
     *
     * @return boolean
     */
    public function existSession()
    {
        return session_status() == PHP_SESSION_ACTIVE && !empty($_SESSION);
    }

    /**
     * Establecer la duración de la sesión
     *
     * @param integer $minutes
     * @return void
     */
    public function setSessionDuration(int $minutes)
    {
        // Convertir minutos a segundos
        $tiempo = $minutes * 60;
        // Configurar el tiempo de la sesión
        session_set_cookie_params($tiempo);
    }

    /**
     * Destruir la sesión
     *
     * @return void
     */
    public function destroySession()
    {
        // Destruir la sesión si está iniciada
        if ($this->existSession()) {
            $_SESSION = array();
            session_unset();
            session_destroy();
        }
    }
}

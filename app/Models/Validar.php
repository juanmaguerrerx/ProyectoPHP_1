<?php

namespace App\Models;

use App\Models\Provincias;
use App\Models\Operarios;
use App\Models\ConexionDB;
use PDO;
use PDOException;

class Validar
{
    protected $datos;

    protected $errores = [];

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function validarTarea()
    {
        $this->validarNif();
        $this->validarPersonaContacto();
        $this->validarTelefono();
        $this->validarEmail();
        $this->validarDescripcion();
        $this->validarDireccion();
        $this->validarPoblacion();
        $this->validarCodigoPostal();

        return $this->errores;
    }

    public function validarTareaMod()
    {
        $this->validarNifMod();
        $this->validarPersonaContactoMod();
        $this->validarTelefonoMod();
        $this->validarEmailMod();
        $this->validarDescripcion();
        $this->validarCodigoPostalMod();

        return $this->errores;
    }

    public function validarUsuario()
    {
        $this->validarNombre();
        $this->validarApellidos();
        $this->validarCorreo();
        $this->validarContrasena();

        return $this->errores;
    }
    public function validarUsuarioMod()
    {
        $this->validarNombre();
        $this->validarApellidos();
        $this->validarCorreoMod();
        $this->validarContrasena();

        return $this->errores;
    }

    public function validarLogin()
    {
        $this->validarEmailLogin();
        $this->validarContrasenaLogin();

        return $this->errores;
    }

    protected function agregarError($campo, $mensaje)
    {
        $this->errores[$campo] = $mensaje;
    }

    protected function validarEmailLogin()
    {
        $oMod = new Operarios;
        if (!($oMod->isExist($this->datos['email']))) {
            $this->agregarError('email', 'El correo electrónico no está registrado.');
            // dd('existe');
        } else {
            // dd('no existe');
            
        }
    }
    protected function validarContrasenaLogin()
    {
        $oMod = new Operarios;
        $contrasenaOp = $oMod->getContrasena($this->datos['email']);
        $contrasenaLogin = $this->datos['contrasena'];
        if (!($contrasenaOp == $contrasenaLogin)) {
            $this->agregarError('contrasena', 'La contraseña es incorrecta');
        }
    }


    protected function validarNif()
    {
        if (empty($this->datos['nif'])) {
            $this->agregarError('nif', 'El NIF o CIF es obligatorio.');
        } else {
            $nif = strtoupper($this->datos['nif']);

            if (!preg_match('/^[XYZ0-9]\d{7}[A-HJ-NP-TV-Z]$/', $nif)) {
                $this->agregarError('nif', 'El formato del NIF no es válido.');
            } else {
                $letraCalculada = $this->calcularLetraNif(substr($nif, 0, -1));

                if ($letraCalculada !== substr($nif, -1)) {
                    $this->agregarError('nif', 'La letra del NIF no es válida.');
                }
            }
        }
    }
    protected function validarNifMod()
    {
        if (empty($this->datos['nif_cliente'])) {
            $this->agregarError('nif_cliente', 'El NIF o CIF es obligatorio.');
        } else {
            $nif = strtoupper($this->datos['nif_cliente']);

            if (!preg_match('/^[XYZ0-9]\d{7}[A-HJ-NP-TV-Z]$/', $nif)) {
                $this->agregarError('nif_cliente', 'El formato del NIF no es válido.');
            } else {
                $letraCalculada = $this->calcularLetraNif(substr($nif, 0, -1));

                if ($letraCalculada !== substr($nif, -1)) {
                    $this->agregarError('nif_cliente', 'La letra del NIF no es válida.');
                }
            }
        }
    }


    protected function calcularLetraNif($numero)
    {
        $resto = $numero % 23;
        $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

        return $letras[$resto];
    }

    protected function validarPersonaContacto()
    {
        if (empty($this->datos['cliente'])) {
            $this->agregarError('cliente', 'El nombre del cliente es obligatorio.');
        }
    }
    protected function validarPersonaContactoMod()
    {
        if (empty($this->datos['nombre_cliente'])) {
            $this->agregarError('nombre_cliente', 'El nombre del cliente es obligatorio.');
        }
    }
    protected function validarNombre()
    {
        if (empty($this->datos['nombre'])) {
            $this->agregarError('nombre', 'El nombre es obligatorio.');
        }
    }


    protected function validarDescripcion()
    {
        if (empty($this->datos['descripcion'])) {
            $this->agregarError('descripcion', 'Es obligatorio agregar una descripcion de la tarea.');
        }
    }


    protected function validarTelefono()
    {
        if (empty($this->datos['telefono'])) {
            $this->agregarError('telefono', 'El número de teléfono es obligatorio.');
        } elseif (!preg_match('/^[0-9]+$/', $this->datos['telefono'])) {
            $this->agregarError('telefono', 'El número de teléfono solo puede contener dígitos.');
        } elseif (strlen($this->datos['telefono']) < 9) {
            $this->agregarError('telefono', 'El número de teléfono debe tener al menos 9 dígitos.');
        } elseif (strlen($this->datos['telefono']) > 15) {
            $this->agregarError('telefono', 'El número de teléfono no debe exceder los 15 dígitos.');
        }
    }
    protected function validarTelefonoMod()
    {
        if (empty($this->datos['telefono_cliente'])) {
            $this->agregarError('telefono_cliente', 'El número de teléfono es obligatorio.');
        } elseif (!preg_match('/^[0-9]+$/', $this->datos['telefono_cliente'])) {
            $this->agregarError('telefono_cliente', 'El número de teléfono solo puede contener dígitos.');
        } elseif (strlen($this->datos['telefono_cliente']) < 9) {
            $this->agregarError('telefono_cliente', 'El número de teléfono debe tener al menos 9 dígitos.');
        } elseif (strlen($this->datos['telefono_cliente']) > 15) {
            $this->agregarError('telefono_cliente', 'El número de teléfono no debe exceder los 15 dígitos.');
        }
    }

    // protected function validarOperario()
    // {
    //     if (empty($this->datos['operario'])) {
    //         $this->agregarError('operario', 'El operario encargado es obligatorio.');
    //     }
    // }

    protected function validarContrasena()
    {
        if (empty($this->datos['contrasena'])) {
            $this->agregarError('contrasena', 'El número de teléfono es obligatorio.');
        }

        // Inicializar un arreglo para almacenar errores
        $erroresContrasena = [];

        // Verificar longitud mínima de 8 caracteres
        if (strlen($this->datos['contrasena']) < 8) {
            $erroresContrasena[] = 'La contraseña debe tener al menos 8 caracteres';
        }

        // Verificar la presencia de al menos un número
        if (!preg_match('/[0-9]/', $this->datos['contrasena'])) {
            $erroresContrasena[] = 'La contraseña debe contener al menos un número';
        }

        // Verificar la presencia de al menos un caracter especial
        if (!preg_match('/[^a-zA-Z0-9]/', $this->datos['contrasena'])) {
            $erroresContrasena[] = 'La contraseña debe contener al menos un caracter especial';
        }

        // Almacenar todos los errores en el atributo 'errores'
        if (!empty($erroresContrasena)) {
            $this->agregarError('contrasena', $erroresContrasena);
        }
    }

    protected function validarApellidos()
    {
        if (empty($this->datos['apellidos'])) {
            $this->agregarError('apellidos', 'Los apellidos son obligatorios.');
        }
    }

    protected function validarPoblacion()
    {
        if (empty($this->datos['poblacion'])) {
            $this->agregarError('poblacion', 'La poblacion es obligatoria.');
        }
    }

    protected function validarCodigoPostal()
    {
        if (empty($this->datos['codigoPostal'])) {
            $this->agregarError('codigoPostal', 'El codigo postal es obligatorio.');
        } elseif ($this->datos['provincia'] != substr($this->datos['codigoPostal'], 0, 2)) {
            $this->agregarError('codigoPostal', 'El codigo postal no coincide con el de la provincia seleccionada');
        }
    }

    protected function validarCodigoPostalMod()
    {
        if (empty($this->datos['codigo_postal'])) {
            $this->agregarError('codigo_postal', 'El codigo postal es obligatorio.');
        } elseif ($this->datos['provincia'] != substr($this->datos['codigo_postal'], 0, 2)) {
            $this->agregarError('codigo_postal', 'El codigo postal no coincide con el de la provincia seleccionada');
        }
    }



    protected function validarDireccion()
    {
        if (empty($this->datos['direccion'])) {
            $this->agregarError('direccion', 'La direccion es obligatoria.');
        }
    }


    protected function validarEmail()
    {
        if (empty($this->datos['email'])) {
            $this->agregarError('email', 'El correo electrónico es obligatorio.');
        } elseif (!filter_var($this->datos['email'], FILTER_VALIDATE_EMAIL)) {
            $this->agregarError('email', 'El formato del correo electrónico no es válido.');
        }
    }
    protected function validarEmailMod()
    {
        if (empty($this->datos['correo_cliente'])) {
            $this->agregarError('correo_cliente', 'El correo electrónico es obligatorio.');
        } elseif (!filter_var($this->datos['correo_cliente'], FILTER_VALIDATE_EMAIL)) {
            $this->agregarError('correo_cliente', 'El formato del correo electrónico no es válido.');
        }
    }

    protected function validarCorreo()
    {
        if (empty($this->datos['correo'])) {
            $this->agregarError('correo', 'El correo electrónico es obligatorio.');
        } elseif (!filter_var($this->datos['correo'], FILTER_VALIDATE_EMAIL)) {
            $this->agregarError('correo', 'El formato del correo electrónico no es válido.');
        }
        $oMod = new Operarios;
        if ($oMod->isExist($this->datos['correo'])) {
            $this->agregarError('correo', 'El correo electrónico ya existe.');
        }
    }
    protected function validarCorreoMod()
    {
        if (empty($this->datos['correo_cliente'])) {
            $this->agregarError('correo', 'El correo electrónico es obligatorio.');
        } elseif (!filter_var($this->datos['correo'], FILTER_VALIDATE_EMAIL)) {
            $this->agregarError('correo', 'El formato del correo electrónico no es válido.');
        }
    }
}

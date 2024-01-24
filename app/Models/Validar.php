<?php

namespace App\Models;

use App\Models\Provincias;
use App\Models\Operarios;
use App\Models\ConexionDB;
use PDO;
use PDOException;

/**
 * Clase encargada de validar los datos de los formularios
 */
class Validar
{
    // Datos de los formularios a validar
    protected $datos;

    // Conjunto de posibles errores
    protected $errores = [];

    /**
     * Constructor
     *
     * @param $datos
     */
    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    /**
     * Funcion para validar el formulario de crear tarea
     *
     * @return array -> array asociativo de errores
     */
    public function validarTarea(): array
    {
        $this->validarNif();
        $this->validarPersonaContacto();
        $this->validarTelefono();
        $this->validarCorreo($this->datos['correo_cliente']);
        $this->validarDescripcion();
        $this->validarDireccion();
        $this->validarPoblacion();
        $this->validarCodigoPostal();

        return $this->errores;
    }

    /**
     * Funcion que valida el formulario de modificar tarea
     *
     * @return array
     */
    public function validarTareaMod(): array
    {
        $this->validarNifMod();
        $this->validarPersonaContactoMod();
        $this->validarTelefonoMod();
        $this->validarCorreo($this->datos['correo_cliente']);
        $this->validarDescripcion();
        $this->validarCodigoPostalMod();

        return $this->errores;
    }

    /**
     * Funcion que valida el formulario de crear usuario
     *
     * @return array
     */
    public function validarUsuario(): array
    {
        $this->validarNombre();
        $this->validarApellidos();
        $this->validarCorreo($this->datos['correo'],true);
        $this->validarContrasena();

        return $this->errores;
    }

    /**
     * Valida el formulario de modificación de usuario
     *
     * @return array
     */
    public function validarUsuarioMod(): array
    {
        $this->validarNombre();
        $this->validarApellidos();
        $this->validarCorreo($this->datos['correo'],true);
        $this->validarContrasena();

        return $this->errores;
    }

    /**
     * Valida el formulario de login
     *
     * @return array
     */
    public function validarLogin(): array
    {
        $this->validarEmailLogin();
        $this->validarContrasenaLogin();

        return $this->errores;
    }

    /**
     * Funcion que agrega errores asociados a un campo
     *
     * @param $campo -> campo en el que se guarda el error
     * @param $mensaje -> mensaje de error que será visualizado por el cliente
     * @return void
     */
    protected function agregarError($campo, $mensaje)
    {
        $this->errores[$campo] = $mensaje;
    }

    /**
     * Validar email en el login
     *
     * @return void
     */
    protected function validarEmailLogin()
    {
        $oMod = new Operarios;

        // Si el correo no esta en la BBDD
        if (!($oMod->isExist($this->datos['email']))) {
            $this->agregarError('email', 'El correo electrónico no está registrado.');
        }
    }
    /**
     * Validar contraseña en el login
     *
     * @return void
     */
    protected function validarContrasenaLogin()
    {
        $oMod = new Operarios;

        // Contraseña real del mail proporcionado
        $contrasenaOp = $oMod->getContrasena($this->datos['email']);

        // Contraseña escrita por el usuario
        $contrasenaLogin = $this->datos['contrasena'];

        // Si no coinciden
        if (!($contrasenaOp == $contrasenaLogin)) {
            $this->agregarError('contrasena', 'La contraseña es incorrecta');
        }
    }

    /**
     * Validar NIF
     *
     * @return void
     */
    protected function validarNif()
    {
        // Si está vacío
        if (empty($this->datos['nif'])) {
            $this->agregarError('nif', 'El NIF o CIF es obligatorio.');
        } else {
            // Pasamos a mayusculas
            $nif = strtoupper($this->datos['nif']);

            // Si no sigue el patron:
            // Comienza con Z Y X o un numero del 0-9
            // Tiene exactamente 7 digitos
            // Y termina en un caracter que puede ser de la A-H o de la J-Z, excluyendo la I y la O
            if (!preg_match('/^[XYZ0-9]\d{7}[A-HJ-NP-TV-Z]$/', $nif)) {
                $this->agregarError('nif', 'El formato del NIF no es válido.');
            } else {

                // Calculamos la letra del dni
                $letraCalculada = $this->calcularLetraNif(substr($nif, 0, -1));

                // Sino coincide con la letra calculada
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

    /**
     * Calcular la letra del DNI
     *
     * @param integer $numero -> numero de dni
     * @return string -> letra correspondiente
     */
    protected function calcularLetraNif(int $numero): string
    {
        $resto = $numero % 23;
        $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

        return $letras[$resto];
    }

    /**
     * Valida campo obligatorio
     *
     * @return void
     */
    protected function validarPersonaContacto()
    {
        if (empty($this->datos['cliente'])) {
            $this->agregarError('cliente', 'El nombre del cliente es obligatorio.');
        }
    }

    /**
     * Valida campo obligatorio
     *
     * @return void
     */
    protected function validarPersonaContactoMod()
    {
        if (empty($this->datos['nombre_cliente'])) {
            $this->agregarError('nombre_cliente', 'El nombre del cliente es obligatorio.');
        }
    }

    /**
     * Valida campo obligatorio
     *
     * @return void
     */
    protected function validarNombre()
    {
        if (empty($this->datos['nombre'])) {
            $this->agregarError('nombre', 'El nombre es obligatorio.');
        }
    }

    /**
     * Valida campo obligatorio
     *
     * @return void
     */
    protected function validarDescripcion()
    {
        if (empty($this->datos['descripcion'])) {
            $this->agregarError('descripcion', 'Es obligatorio agregar una descripcion de la tarea.');
        }
    }

    /**
     * Valida numero de telefono
     *
     * @return void
     */
    protected function validarTelefono()
    {
        // Si esta vacio
        if (empty($this->datos['telefono'])) {
            $this->agregarError('telefono', 'El número de teléfono es obligatorio.');

            // Si contiene algun caracter que no sea numérico
        } elseif (!preg_match('/^[0-9]+$/', $this->datos['telefono'])) {
            $this->agregarError('telefono', 'El número de teléfono solo puede contener dígitos.');

            // Si es menor que 9
        } elseif (strlen($this->datos['telefono']) < 9) {
            $this->agregarError('telefono', 'El número de teléfono debe tener al menos 9 dígitos.');

            // Si es mayor que 15
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

    /**
     * Validar contraseña
     *
     * @return void
     */
    protected function validarContrasena()
    {
        // Si está vacia
        if (empty($this->datos['contrasena'])) {
            $this->agregarError('contrasena', 'La contraseña es obligatoria');
        }

        $erroresContrasena = [];

        // Su no tiene menos de 8 caracteres
        if (strlen($this->datos['contrasena']) < 8) {
            $erroresContrasena[] = 'La contraseña debe tener al menos 8 caracteres';
        }

        // Si no tiene al menos un numero 
        if (!preg_match('/[0-9]/', $this->datos['contrasena'])) {
            $erroresContrasena[] = 'La contraseña debe contener al menos un número';
        }

        // Si no tiene al menos un caracter especial
        if (!preg_match('/[^a-zA-Z0-9]/', $this->datos['contrasena'])) {
            $erroresContrasena[] = 'La contraseña debe contener al menos un caracter especial';
        }

        // Si el registro no está vacio agregar los errores que hayan
        if (!empty($erroresContrasena)) {
            $this->agregarError('contrasena', $erroresContrasena);
        }
    }

    /**
     * Validar que un registro no esté vacío
     *
     * @return void
     */
    protected function validarApellidos()
    {
        if (empty($this->datos['apellidos'])) {
            $this->agregarError('apellidos', 'Los apellidos son obligatorios.');
        }
    }

    /**
     * Validar que un registro no esté vacío
     *
     * @return void
     */
    protected function validarPoblacion()
    {
        if (empty($this->datos['poblacion'])) {
            $this->agregarError('poblacion', 'La poblacion es obligatoria.');
        }
    }

    /**
     * Validar código postal
     *
     * @return void
     */
    protected function validarCodigoPostal()
    {
        //Si está vacío
        if (empty($this->datos['codigoPostal'])) {
            $this->agregarError('codigoPostal', 'El codigo postal es obligatorio.');

            //Si no coincide con el codigo de la provincia registrada
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


    /**
     * Validar que no esté la direccion vacía
     *
     * @return void
     */
    protected function validarDireccion()
    {
        if (empty($this->datos['direccion'])) {
            $this->agregarError('direccion', 'La direccion es obligatoria.');
        }
    }

    /**
     * Funcion para validar email 
     *
     * @param string $email
     * @param boolean $checkExist -> true | Si tiene que comprobar si existe el email 
     * @return void
     */
    protected function validarCorreo(string $email, bool $checkExist = false)
    {
        
        if($checkExist){
            $oMod = new Operarios;
            if ($oMod->isExist($email)) {
                $this->agregarError('correo', 'El correo electrónico ya existe.');
            }
        }
        if (empty($email)) {
            $this->agregarError('correo', 'El correo electrónico es obligatorio.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->agregarError('correo', 'El formato del correo electrónico no es válido.');
        }
        
       
    }
    
}

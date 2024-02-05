<?php

namespace App\Models;

use App\Models\Provincias;
use App\Models\Operarios;
use App\Models\ConexionDB;
use PDO;
use PDOException;

use function PHPUnit\Framework\isEmpty;

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
        $this->validarNif($this->datos['nif']);
        $this->validarPersonaContacto($this->datos['cliente']);
        $this->validarTelefono();
        $this->validarCorreo($this->datos['email']);
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
    public function validarTareaMod(bool $operario = null): array
    {
        if ($operario){
            $this->validarFechaRealizacion();
            return $this->errores;
        }
        $this->validarNif($this->datos['nif_cliente']);
        $this->validarPersonaContacto($this->datos['nombre_cliente']);
        $this->validarTelefonoMod();
        $this->validarCorreo($this->datos['correo_cliente']);
        $this->validarDescripcion();
        $this->validarCodigoPostalMod();
        $this->validarFechaRealizacion();

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
        $this->validarCorreo($this->datos['correo']);
        $this->validarContrasena();

        return $this->errores;
    }

    /**
     * Valida el formulario de modificación de usuario
     *
     * @return array
     */
    public function validarUsuarioMod(bool $emailCheck = false): array
    {
        $this->validarNombre();
        $this->validarApellidos();
        $this->validarCorreo($this->datos['correo'], false, $emailCheck);
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
        if (empty($this->datos['email'])) {
            $this->agregarError('email', 'Debe rellenar el correo');
        }
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

        if (empty($this->datos['contrasena'])) {
            $this->agregarError('contrasena', 'Debe escribir la contraseña');
        }

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
     * Validar el NIF teniendo en cuenta las reglas de formato del mismo
     *
     * @param $nif_val
     * @return void
     */
    protected function validarNif($nif_val = null)
    {
        // Si está vacío
        if (empty($nif_val)) {
            $this->agregarError('nif', 'El NIF o CIF es obligatorio.');
        } else {
            // Pasamos a mayusculas
            $nif = strtoupper($nif_val);

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
     * Validar el nombre del cliente
     *
     * @param $nombre -> nombre del cliente
     * @return void
     */
    protected function validarPersonaContacto($nombre = null)
    {
        if (empty($nombre)) {
            // dd(true);
            $this->agregarError('cliente', 'El nombre del cliente es obligatorio.');
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
     * @param boolean $mod -> false | Si no está modificando el correo
     * @return void
     */
    protected function validarCorreo($email = null, bool $checkExist = true, bool $mod = false)
    {
        $oMod = new Operarios;
        if ($email != null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->agregarError('correo', 'El formato no es válido');
            }
            if ($checkExist || $mod) {
                if ($oMod->isExist($email)) {
                    $this->agregarError('correo', 'El correo electrónico ya existe.');
                }
            }
        }else $this->agregarError('correo','El campo correo no puede estar vacío');
    }

    /**
     * Valida la fecha de realizacion
     *
     * @return void
     */
    protected function validarFechaRealizacion()
    {
        $estado = $this->datos['estado'];
        $fecha = $this->datos['fecha_realizacion'];
        $tMod = new Tareas;

        //Si no esta en proceso (p.e realizada) y tiene fecha nula
        if ($estado != 'P' && $fecha == null) {
            $this->agregarError('fecha', 'Si no está en proceso necesita fecha de realización');
            //Si está en proceso y tiene fecha
        } elseif ($estado == 'P' && $fecha != null) {
            $this->agregarError('fecha', 'Si no está realizada no puede tener fecha de realización');
            //Si la fecha es superior a la actual
        } elseif ($fecha > date('Y-m-d') && $fecha != null) {
            $this->agregarError('fecha', 'La fecha no puede ser superior a la actual');
        } else if ($fecha < $this->datos['fecha_creacion'] && $fecha != null) {
            $this->agregarError('fecha', 'La fecha no puede ser menor que la fecha de creacion (' . $this->datos['fecha_creacion'] . ')');
        }
    }
}

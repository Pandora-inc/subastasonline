<?php
namespace www\App\Modelo;

use Exception;
use www\App\Core\DBConnection;

/**
 *
 * @author IVANB
 *
 */
class Usuarios implements iModeloStandar
{

    /**
     * Numero identificador del usuario.
     *
     * @var int
     */
    protected $id;

    /**
     * Nombre del usuario.
     *
     * @var string
     */
    protected $nombre;

    /**
     * Apellido del usuario.
     *
     * @var string
     */
    protected $apellido;

    /**
     * Email asociado a la cuenta de usuario.
     *
     * @var string
     */
    protected $email;

    /**
     * Documento del usuario.
     *
     * @var string
     */
    protected $documento;

    /**
     * Dirección del usuario.
     *
     * @var string
     */
    protected $direccion;

    /**
     * Localidad de la dirección del usuario.
     *
     * @var string
     */
    protected $localidad;

    /**
     * Provincia de la dirección del usuario.
     *
     * @var string
     */
    protected $provincia;

    /**
     * Pais de la dirección del usuario.
     *
     * @var string
     */
    protected $pais;

    /**
     * Numero de celular del usuario.
     *
     * @var string
     */
    protected $celular;

    /**
     * Prefijo del teléfono del usuario.
     *
     * @var int
     */
    protected $prefijo;

    /**
     * Apodo del usuario (Nickname)
     *
     * @var string
     */
    protected $apodo;

    /**
     * Contraseña de la cuenta.
     * Debe estar cifrada con password_hash()
     *
     * @var string
     */
    protected $password;

    /**
     * Se encuentra el usuario activo?
     *
     * @var bool
     */
    protected $estado;

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $parametros = array();

        $parametros['nombre'] = $this->nombre;
        $parametros['apellido'] = $this->apellido;
        $parametros['email'] = $this->email;
        $parametros['documento'] = $this->documento;
        $parametros['direccion'] = $this->direccion;
        $parametros['localidad'] = $this->localidad;
        $parametros['provincia'] = $this->provincia;
        $parametros['pais'] = $this->pais;
        $parametros['celular'] = $this->celular;
        $parametros['prefijo'] = $this->prefijo;
        $parametros['apodo'] = $this->apodo;
        $parametros['password'] = $this->password;

        return $parametros;
    }

    /**
     * Inserta los datos del usuario en la base de datos.
     * Previamente a él deben haberse inicializado todos los parámetros de la clase.
     *
     * @return bool
     */
    public function nuevo(): bool
    {
        $db = DBConnection::getConnection();

        $sql = "INSERT INTO martinsa_subastas_online.usuarios (nombre, apellido, email, documento, direccion, localidad, provincia, pais, celular, prefijo, apodo, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $parametros = array();
        $parametros = $this->jsonSerialize();

        if ($db->query($sql, true, $parametros)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::getById()
     */
    public function getById(int $id): object
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT * FROM martinsa_subastas_online.usuariosWHERE id = ?";

        $parametros = array();
        $parametros[] = $id;

        $rest = $db->query($sql, true, $parametros);

        if ($fila = $db->fetchObject($rest, self::class)) {
            return $fila;
        } else {
            throw new Exception("Usuario no encontrado");
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::deleteThis()
     */
    public function deleteThis(): bool
    {
        return $this->deleteById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::deleteById()
     */
    public function deleteById(int $id): bool
    {
        $db = DBConnection::getConnection();

        $sql = "UPDATE martinsa_subastas_online.usuarios SET estado=false WHERE id=?";

        $parametros = array();
        $parametros['id'] = $id;

        if ($db->query($sql, true, $parametros)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::getByThis()
     */
    public function getByThis(): object
    {
        return $this->getById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::updateThis()
     */
    public function updateThis(): bool
    {
        return $this->updateById($this->id, $this->jsonSerialize());
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::updateById()
     */
    public function updateById(int $id, array $parametros): bool
    {
        $db = DBConnection::getConnection();

        $datos = "";
        $param = array();

        foreach ($parametros as $key => $value) {
            if ($datos != "") {
                $datos .= ", ";
            }

            $datos .= $key . " = :" . $key;

            $param[$key] = $value;
        }

        $sql = "UPDATE martinsa_subastas_online.usuarios SET " . $datos . " WHERE id=?";

        $param['id'] = $id;

        if ($db->query($sql, true, $param)) {
            return true;
        } else {
            return false;
        }
    }

    /* ************* */
    /* ** GETTERS ** */
    /* ************* */

    /**
     *
     * @return number
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     *
     * @return string
     */
    public function getApellido(): string
    {
        return $this->apellido;
    }

    /**
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     *
     * @return string
     */
    public function getDocumento(): string
    {
        return $this->documento;
    }

    /**
     *
     * @return string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     *
     * @return string
     */
    public function getLocalidad(): string
    {
        return $this->localidad;
    }

    /**
     *
     * @return string
     */
    public function getProvincia(): string
    {
        return $this->provincia;
    }

    /**
     *
     * @return string
     */
    public function getPais(): string
    {
        return $this->pais;
    }

    /**
     *
     * @return string
     */
    public function getCelular(): string
    {
        return $this->celular;
    }

    /**
     *
     * @return number
     */
    public function getPrefijo(): int
    {
        return $this->prefijo;
    }

    /**
     *
     * @return string
     */
    public function getApodo(): string
    {
        return $this->apodo;
    }

    /**
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /* ************* */
    /* ** SETTERS ** */
    /* ************* */
    public function setId(int $id)
    {
        if (is_int($id)) {
            $this->id = $id;
        } else {
            throw new \Exception("El id debe ser un entero.", 66);
        }
    }

    /**
     *
     * @param string $nombre
     */
    public function setNombre(string $nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     *
     * @param string $apellido
     */
    public function setApellido(string $apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     *
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     *
     * @param string $documento
     */
    public function setDocumento(string $documento)
    {
        $this->documento = $documento;
    }

    /**
     *
     * @param string $direccion
     */
    public function setDireccion(string $direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     *
     * @param string $localidad
     */
    public function setLocalidad(string $localidad)
    {
        $this->localidad = $localidad;
    }

    /**
     *
     * @param string $provincia
     */
    public function setProvincia(string $provincia)
    {
        $this->provincia = $provincia;
    }

    /**
     *
     * @param string $pais
     */
    public function setPais(string $pais)
    {
        $this->pais = $pais;
    }

    /**
     *
     * @param string $celular
     */
    public function setCelular(string $celular)
    {
        if (is_numeric($celular)) {
            $this->celular = $celular;
        } else {
            throw new \Exception("El numero de celular debe contener solo caracteres numericos.", 66);
        }
    }

    /**
     *
     * @param number $prefijo
     */
    public function setPrefijo(int $prefijo)
    {
        $this->prefijo = $prefijo;
    }

    /**
     *
     * @param string $apodo
     */
    public function setApodo(string $apodo)
    {
        $this->apodo = $apodo;
    }

    /**
     *
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}
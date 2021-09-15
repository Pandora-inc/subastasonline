<?php
namespace Modelo;

require_once '/home/martinsa/public_html/autoload.php';

use DateTime;
use www\App\Modelo\iModeloStandar;

/**
 * algo
 *
 * @author IVANB
 *
 */
class Autores implements iModeloStandar
{

    private const BASE_DB = "martinsa_base_prueba.";

    // SELECT `id``nombre``Descripcion``anio_nac``anio_def``id_escuela``id_tecnica` FROM `aux_autor` WHERE 1

    /**
     * Numero identificador del autor.
     *
     * @var int
     */
    private $id;

    /**
     * Nombre del autor, no pueden repetirse.
     *
     * @var string
     */
    private $nombre;

    /**
     * Descripción del autor.
     *
     * @var string
     */
    private $Descripcion;

    /**
     * Año de nacimiento
     *
     * @var DateTime
     */
    private $anio_nac;

    /**
     * Año defuncion
     *
     * @var DateTime
     */
    private $anio_def;

    /**
     * Escuela por defecto a traer con el autor
     * FIXME esto deberia ser la relacion con otro tipo de dato escuela.
     *
     * @var string
     */
    private $id_escuela;

    /**
     * técnica por defecto a traer con el autor
     * FIXME esto deberia ser la relacion con otro tipo de dato tecnica.
     *
     * @var string
     */
    private $id_tecnica;

    /**
     */
    public function __construct()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function jsonSerialize()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::nuevo()
     */
    public function nuevo(): bool
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::deleteThis()
     */
    public function deleteThis()
    {
        return $this->deleteById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::getById()
     */
    public function getById(int $id): self
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::deleteById()
     */
    public function deleteById($id)
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::getByThis()
     */
    public function getByThis()
    {
        return $this->getById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::updateThis()
     */
    public function updateThis()
    {
        return $this->updateById($this->id, $this->jsonSerialize());
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::updateById()
     */
    public function updateById($id, array $parametros)
    {}

    /**
     * Retorna el valor del campo $id
     *
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retorna el valor del campo $nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Retorna el valor del campo $Descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->Descripcion;
    }

    /**
     * Retorna el valor del campo $anio_nac
     *
     * @return DateTime
     */
    public function getAnio_nac()
    {
        return $this->anio_nac;
    }

    /**
     * Retorna el valor del campo $anio_def
     *
     * @return DateTime
     */
    public function getAnio_def()
    {
        return $this->anio_def;
    }

    /**
     * Retorna el valor del campo $id_escuela
     *
     * @return string
     */
    public function getId_escuela()
    {
        return $this->id_escuela;
    }

    /**
     * Retorna el valor del campo $id_tecnica
     *
     * @return string
     */
    public function getId_tecnica()
    {
        return $this->id_tecnica;
    }

    /**
     * Funcion de carga de datos del parametro $id
     *
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Funcion de carga de datos del parametro $nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Funcion de carga de datos del parametro $Descripcion
     *
     * @param string $Descripcion
     */
    public function setDescripcion($Descripcion)
    {
        $this->Descripcion = $Descripcion;
    }

    /**
     * Funcion de carga de datos del parametro $anio_nac
     *
     * @param DateTime $anio_nac
     */
    public function setAnio_nac($anio_nac)
    {
        $this->anio_nac = $anio_nac;
    }

    /**
     * Funcion de carga de datos del parametro $anio_def
     *
     * @param DateTime $anio_def
     */
    public function setAnio_def($anio_def)
    {
        $this->anio_def = $anio_def;
    }

    /**
     * Funcion de carga de datos del parametro $id_escuela
     *
     * @param string $id_escuela
     */
    public function setId_escuela($id_escuela)
    {
        $this->id_escuela = $id_escuela;
    }

    /**
     * Funcion de carga de datos del parametro $id_tecnica
     *
     * @param string $id_tecnica
     */
    public function setId_tecnica($id_tecnica)
    {
        $this->id_tecnica = $id_tecnica;
    }
}


<?php
namespace www\App\Modelo;

/**
 *
 * @author IVANB
 *
 */
class Categorias implements iModeloStandar
{

    private const BASE_DB = "martinsa_base_prueba.";

    /**
     * Identificador de la categoria.
     *
     * @var int
     */
    private $id;

    /**
     * Nombre de la categoria.
     *
     * @var string
     */
    private $nombre;

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
     * @see \www\App\Modelo\iModeloStandar::nuevo()
     */
    public function nuevo()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \www\App\Modelo\iModeloStandar::deleteThis()
     */
    public function deleteThis()
    {
        return $this->deleteById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \www\App\Modelo\iModeloStandar::getById()
     */
    public function getById($id)
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \www\App\Modelo\iModeloStandar::deleteById()
     */
    public function deleteById($id)
    {}

    /**
     * (non-PHPdoc)
     *
     * @see \www\App\Modelo\iModeloStandar::getByThis()
     */
    public function getByThis()
    {
        return $this->getById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \www\App\Modelo\iModeloStandar::updateThis()
     */
    public function updateThis()
    {
        return $this->updateById($this->id, $this->jsonSerialize());
    }

    /**
     * (non-PHPdoc)
     *
     * @see \www\App\Modelo\iModeloStandar::updateById()
     */
    public function updateById($id, array $parametros)
    {}
}


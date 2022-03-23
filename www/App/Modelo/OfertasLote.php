<?php
namespace www\App\Modelo;

use DateTime;
use www\App\Core\DBConnection;

/**
 *
 * @author IVANB
 *
 */
class OfertasLote implements iModeloStandar
{

    // private const BASE_DB = "martinsa_base_prueba.";
    // private const BASE_DB_SUBASTAS = "martinsa_subastas_online.";

    /**
     *
     * @var DateTime
     */
    private DateTime $fecha;

    /**
     *
     * @var float
     */
    private float $importe;

    /**
     *
     * @var Usuarios
     */
    private Usuarios $usuario;

    /**
     */
    public function __construct(DateTime $fecha = null, float $importe = null, Usuarios $usuario = null)
    {
        if ($fecha != null) {
            $this->setFecha($fecha);
        }
        if ($importe != null) {
            $this->setImporte($importe);
        }
        if ($usuario != null) {
            $this->setUsuario($usuario);
        }
    }

    public function getByLote(int $idLote): array
    {
        $db = DBConnection::getConnection();
        // %Y-%c-%d %H:%i:%s
        $sql = "SELECT DATE_FORMAT(fecha, '%Y-%c-%d %H %i') AS fecha, importe, id_usuario FROM " . self::BASE_DB_SUBASTAS . "ofertas WHERE id_lote = :id_lote ORDER BY fecha";

        $parametros = array();
        $parametros[] = $idLote;

        $ofertas = array();

        $rest = $db->query($sql, true, $parametros);

        if ($fila = $rest->fetchAll()) {

            foreach ($fila as $dato) {
                // 'Y-m-d H:i:s'
                
//                 print_r($dato);
                $ofertas[] = new OfertasLote(DateTime::createFromFormat('Y-m-d H i', $dato['fecha']), $dato['importe'], (new Usuarios())->getById($dato['id_usuario']));
            }
        }
        return $ofertas;
    }

    /**
     * Retorna el valor del campo $fecha
     *
     * @return DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Retorna el valor del campo $importe
     *
     * @return number
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Retorna el valor del campo $usuario
     *
     * @return \www\App\Modelo\Usuarios
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Funcion de carga de datos del parametro $fecha
     *
     * @param DateTime $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Funcion de carga de datos del parametro $importe
     *
     * @param number $importe
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;
    }

    /**
     * Funcion de carga de datos del parametro $usuario
     *
     * @param \www\App\Modelo\Usuarios $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function jsonSerialize()
    {}

    public function nuevo_id(int $id_lote): bool
    {
        $db = DBConnection::getConnection();

        // $sql = "INSERT INTO martinsa_subastas_online.usuarios (nombre, apellido, email, documento, direccion, localidad, provincia, pais, celular, prefijo, apodo, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $sql = "INSERT INTO " . self::BASE_DB_SUBASTAS . "ofertas (id_lote, id_usuario, importe) VALUES (:id_lote, :id_usuario, :importe)";

        $parametros = array();
        $parametros['id_lote'] = $id_lote;
        $parametros['id_usuario'] = $this->getUsuario()->getId();
        $parametros['importe'] = $this->getImporte();

        // print_r("<br>");
        // print_r($sql);
        // print_r("<br>");
        // print_r($parametros);
        // print_r("<br>");

        if ($db->query($sql, true, $parametros)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteThis(): bool
    {}

    public function getById(int $id): object
    {}

    public function deleteById(int $id): bool
    {}

    public function getByThis(): object
    {}

    public function updateThis(): bool
    {}

    public function updateById(int $id, array $parametros): bool
    {}

    public function nuevo(): bool
    {}
}


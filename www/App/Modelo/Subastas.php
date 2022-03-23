<?php
namespace www\App\Modelo;

use DateTime;
use Exception;
use PDO;
use www\App\Core\DBConnection;

/**
 *
 * @author IVANB
 *
 */
class Subastas implements iModeloStandar
{

    // private const BASE_DB = "martinsa_base_prueba.";

    // private const BASE_DB_SUBASTAS = "martinsa_subastas_online.";

    /**
     * Numero identificador de la subasta
     *
     * @var int
     */
    private $id;

    /**
     * Titulo que se mostrara en la pantalla de la subasta.
     *
     * @var string
     */
    private $titulo;

    /**
     * Nombre de la imagen de la portada de la subasta.
     *
     * @var string
     */
    private $imagen_nombre;

    /**
     * En caso de necesitar agregar una imagen que no se encuentre registrada en los servidores.
     *
     * @var string
     */
    private $imagen_link;

    /**
     * Descripcion de la subasta para incorporar en la pagina.
     *
     * @var string
     */
    private $descripcion;

    /**
     * Numero de la subasta.
     *
     * @var int
     */
    private $nro;

    /**
     * Noches?
     *
     * @var int
     */
    private $noches;

    /**
     * Fecha de inicio de la subasta.
     *
     * @var DateTime
     */
    private $fechainicio;

    /**
     * Fecha de finalizacion de la subasta.
     *
     * @var DateTime
     */
    private $fechafin;

    /**
     * Fecha que se dio de alta.
     *
     * @var DateTime
     */
    private $fechacarga;

    /**
     * estado de la subasta.
     *
     * @var int
     */
    private $status;

    /**
     * Porcentaje de comicion que se le aplica
     *
     * @var int
     */
    private $comision;

    /**
     * Monto de iva a asociarle.
     *
     * @var int
     */
    private $iva;

    /**
     * Se encuentra errada la subasta.
     *
     * @var bool
     */
    private $cerrada;

    /**
     * Acceso al catalogo digital.
     *
     * @var string
     */
    private $linkcatalogo3;

    /**
     * Indica si la subasta debe mostrarse en la pagina de subastas online.
     *
     * @var bool
     */
    private $subasta_online;

    /**
     *
     * @var \DateTime
     */
    private $hora_inicio;

    /**
     *
     * @var \DateTime
     */
    private $hora_fin;

    /**
     *
     * @var Lotes[]
     */
    private $lotes;

    // FIXME falta agregar un array con los lotes que tiene esta bubasta

    /**
     */
    public function __construct()
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function jsonSerialize(): array
    {
        $parametros = array();

        $parametros['id'] = $this->id;
        $parametros['titulo'] = $this->titulo;
        $parametros['imagen_nombre'] = $this->imagen_nombre;
        $parametros['imagen_link'] = $this->imagen_link;
        $parametros['descripcion'] = $this->descripcion;
        $parametros['nro'] = $this->nro;
        $parametros['noches'] = $this->noches;
        $parametros['fechainicio'] = $this->fechainicio;
        $parametros['hora_inicio'] = $this->hora_inicio;
        $parametros['fechafin'] = $this->fechafin;
        $parametros['hora_fin'] = $this->hora_fin;
        $parametros['fechacarga'] = $this->fechacarga;
        $parametros['status'] = $this->status;
        $parametros['comision'] = $this->comision;
        $parametros['iva'] = $this->iva;
        $parametros['cerrada'] = $this->cerrada;
        $parametros['linkcatalogo3'] = $this->linkcatalogo3;
        $parametros['subasta_online'] = $this->subasta_online;

        return $parametros;
    }

    public function nuevo(): bool
    {}

    public function deleteThis(): bool
    {
        return $this->deleteById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::getById()
     */
    public function getById(int $id): object
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT

subastas.id, subastas.nro , subastas.noches, subastas.fechainicio, subastas.fechainicio, subastas.fechacarga, subastas.status, subastas.comision, subastas.iva
, subastas.cerrada, subastas.linkcatalogo3, subastas.subasta_online,
subastas_online.titulo, subastas_online.descripcion, subastas_online.imagen_nombre, subastas_online.imagen_link, subastas_online.hora_inicio, subastas_online.hora_fin

 FROM " . self::BASE_DB . "subastas LEFT JOIN " . self::BASE_DB_SUBASTAS . "subastas_online ON subastas_online.id = subastas.id WHERE subastas.id = ?";

        $parametros = array();
        $parametros[] = $id;

        $rest = $db->query($sql, true, $parametros);

        if ($fila = $rest->fetchAll(PDO::FETCH_CLASS, self::class)) {

            $fila[0]->setLotes((new Lotes())->getBySubasta($id));

            return $fila[0];
        } else {
            throw new Exception("Usuario no encontrado");
        }
    }

    public function getByLoteId(int $id): object
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT

subastas.id, subastas.nro , subastas.noches, subastas.fechainicio, subastas.fechainicio, subastas.fechacarga, subastas.status, subastas.comision, subastas.iva
, subastas.cerrada, subastas.linkcatalogo3, subastas.subasta_online,
subastas_online.titulo, subastas_online.descripcion, subastas_online.imagen_nombre, subastas_online.imagen_link, subastas_online.hora_inicio, subastas_online.hora_fin

 FROM " . self::BASE_DB . "subastas LEFT JOIN " . self::BASE_DB_SUBASTAS . "subastas_online ON subastas_online.id = subastas.id WHERE subastas.id = (SELECT subasta FROM " . self::BASE_DB . "lotes WHERE lotes.id = ?)";

        $parametros = array();
        $parametros[] = $id;

        $rest = $db->query($sql, true, $parametros);

        if ($fila = $rest->fetchAll(PDO::FETCH_CLASS, self::class)) {

            // $fila[0]->setLotes((new Lotes())->getBySubasta($id));

            return $fila[0];
        } else {
            throw new Exception("Usuario no encontrado");
        }
    }

    /**
     * Busca todas las subastas que sean online y que no esten cerradas y retorna un array con las mismas.
     *
     * @return array
     */
    public function getActivas(): array
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT
subastas.id, subastas.nro , subastas.noches, subastas.fechainicio, subastas.fechainicio, subastas.fechacarga, subastas.status, subastas.comision, subastas.iva
, subastas.cerrada, subastas.linkcatalogo3, subastas.subasta_online,
subastas_online.titulo, subastas_online.descripcion, subastas_online.imagen_nombre, subastas_online.imagen_link, subastas_online.hora_inicio, subastas_online.hora_fin
FROM " . self::BASE_DB . "subastas LEFT JOIN " . self::BASE_DB_SUBASTAS . "subastas_online ON subastas_online.id = subastas.id WHERE subastas.status = 1 AND subasta_online = 1 AND cerrada = 0";

        $rest = $db->query($sql);

        // $resultado = $rest->fetchAll();

        // print_r($resultado);
        return $rest->fetchAll(PDO::FETCH_CLASS, self::class);
        // return $rest->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Busca todas las subastas que sean online y que esten cerradas y retorna un array con las mismas.
     *
     * @return array
     */
    public function getCerradas(): array
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT
subastas.id, subastas.nro , subastas.noches, subastas.fechainicio, subastas.fechainicio, subastas.fechacarga, subastas.status, subastas.comision, subastas.iva
, subastas.cerrada, subastas.linkcatalogo3, subastas.subasta_online,
subastas_online.titulo, subastas_online.descripcion, subastas_online.imagen_nombre, subastas_online.imagen_link, subastas_online.hora_inicio, subastas_online.hora_fin

 FROM " . self::BASE_DB . "subastas LEFT JOIN " . self::BASE_DB_SUBASTAS . "subastas_online ON subastas_online.id = subastas.id WHERE subasta_online = 1 AND cerrada = 1";

        $rest = $db->query($sql);

        return $rest->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public function deleteById(int $id): bool
    {}

    public function getByThis(): object
    {
        return $this->getById($this->id);
    }

    public function updateThis(): bool
    {
        return $this->updateById($this->id, $this->jsonSerialize());
    }

    public function updateById(int $id, array $parametros): bool
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
     * Retorna el valor del campo $titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Retorna el valor del campo $imagen_nombre
     *
     * @return string
     */
    public function getImagen_nombre()
    {
        return $this->imagen_nombre;
    }

    /**
     * Retorna el valor del campo $imagen_link
     *
     * @return string
     */
    public function getImagen_link()
    {
        return $this->imagen_link;
    }

    /**
     * Retorna el valor del campo $descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Retorna el valor del campo $nro
     *
     * @return number
     */
    public function getNro()
    {
        return $this->nro;
    }

    /**
     * Retorna el valor del campo $noches
     *
     * @return number
     */
    public function getNoches()
    {
        return $this->noches;
    }

    /**
     * Retorna el valor del campo $fechainicio
     *
     * @return DateTime
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Retorna el valor del campo $fechafin
     *
     * @return DateTime
     */
    public function getFechafin()
    {
        return $this->fechafin;
    }

    /**
     * Retorna el valor del campo $fechacarga
     *
     * @return DateTime
     */
    public function getFechacarga()
    {
        return $this->fechacarga;
    }

    /**
     * Retorna el valor del campo $status
     *
     * @return number
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Retorna el valor del campo $comision
     *
     * @return number
     */
    public function getComision()
    {
        return $this->comision;
    }

    /**
     * Retorna el valor del campo $iva
     *
     * @return number
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Retorna el valor del campo $cerrada
     *
     * @return boolean
     */
    public function isCerrada()
    {
        return $this->cerrada;
    }

    /**
     * Retorna el valor del campo $linkcatalogo3
     *
     * @return string
     */
    public function getLinkcatalogo3()
    {
        return $this->linkcatalogo3;
    }

    /**
     * Retorna el valor del campo $subasta_online
     *
     * @return boolean
     */
    public function isSubasta_online()
    {
        return $this->subasta_online;
    }

    /**
     * Retorna el valor del campo $hora_inicio
     *
     * @return string
     */
    public function getHora_inicio(): string
    {
        return ($this->hora_inicio == NULL ? "" : $this->hora_inicio);
    }

    /**
     * Retorna el valor del campo $hora_fin
     *
     * @return string
     */
    public function getHora_fin(): string
    {
        return ($this->hora_fin == NULL ? "" : $this->hora_fin);
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
     * Funcion de carga de datos del parametro $titulo
     *
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Funcion de carga de datos del parametro $imagen_nombre
     *
     * @param string $imagen_nombre
     */
    public function setImagen_nombre($imagen_nombre)
    {
        $this->imagen_nombre = $imagen_nombre;
    }

    /**
     * Funcion de carga de datos del parametro $imagen_link
     *
     * @param string $imagen_link
     */
    public function setImagen_link($imagen_link)
    {
        $this->imagen_link = $imagen_link;
    }

    /**
     * Funcion de carga de datos del parametro $descripcion
     *
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Funcion de carga de datos del parametro $nro
     *
     * @param number $nro
     */
    public function setNro($nro)
    {
        $this->nro = $nro;
    }

    /**
     * Funcion de carga de datos del parametro $noches
     *
     * @param number $noches
     */
    public function setNoches($noches)
    {
        $this->noches = $noches;
    }

    /**
     * Funcion de carga de datos del parametro $fechainicio
     *
     * @param DateTime $fechainicio
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;
    }

    /**
     * Funcion de carga de datos del parametro $fechafin
     *
     * @param DateTime $fechafin
     */
    public function setFechafin($fechafin)
    {
        $this->fechafin = $fechafin;
    }

    /**
     * Funcion de carga de datos del parametro $fechacarga
     *
     * @param DateTime $fechacarga
     */
    public function setFechacarga($fechacarga)
    {
        $this->fechacarga = $fechacarga;
    }

    /**
     * Funcion de carga de datos del parametro $status
     *
     * @param number $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Funcion de carga de datos del parametro $comision
     *
     * @param number $comision
     */
    public function setComision($comision)
    {
        $this->comision = $comision;
    }

    /**
     * Funcion de carga de datos del parametro $iva
     *
     * @param number $iva
     */
    public function setIva($iva)
    {
        $this->iva = $iva;
    }

    /**
     * Funcion de carga de datos del parametro $cerrada
     *
     * @param boolean $cerrada
     */
    public function setCerrada($cerrada)
    {
        $this->cerrada = $cerrada;
    }

    /**
     * Funcion de carga de datos del parametro $linkcatalogo3
     *
     * @param string $linkcatalogo3
     */
    public function setLinkcatalogo3($linkcatalogo3)
    {
        $this->linkcatalogo3 = $linkcatalogo3;
    }

    /**
     * Funcion de carga de datos del parametro $hora_inicio, el string debe ser del formato hh:mm
     *
     * @param string $hora
     */
    public function setHoraInicio(string $hora)
    {
        $this->hora_inicio = date("H:m", strtotime($hora));
    }

    /**
     * Funcion de carga de datos del parametro $hora_fin, el string debe ser del formato hh:mm
     *
     * @param string $hora
     */
    public function setHoraFin(string $hora)
    {
        $this->hora_fin = date("H:m", strtotime($hora));
    }

    /**
     * Funcion de carga de datos del parametro $subasta_online
     *
     * @param boolean $subasta_online
     */
    public function setSubasta_online($subasta_online)
    {
        $this->subasta_online = $subasta_online;
    }

    /**
     * Retorna el valor del campo $lotes
     *
     * @return multitype:\www\App\Modelo\Lotes
     */
    public function getLotes()
    {
        return $this->lotes;
    }

    /**
     * Funcion de carga de datos del parametro $hora_inicio
     *
     * @param DateTime $hora_inicio
     */
    public function setHora_inicio($hora_inicio)
    {
        $this->hora_inicio = $hora_inicio;
    }

    /**
     * Funcion de carga de datos del parametro $hora_fin
     *
     * @param DateTime $hora_fin
     */
    public function setHora_fin($hora_fin)
    {
        $this->hora_fin = $hora_fin;
    }

    /**
     * Funcion de carga de datos del parametro $lotes
     *
     * @param multitype:\www\App\Modelo\Lotes $lotes
     */
    public function setLotes($lotes)
    {
        $this->lotes = $lotes;
    }
}


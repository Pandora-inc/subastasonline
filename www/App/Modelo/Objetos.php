<?php
namespace www\App\Modelo;

use DateTime;
use Exception;
use www\App\Core\DBConnection;

/**
 *
 * @author IVANB
 *        
 */
class Objetos implements iModeloStandar
{

    /**
     * Array con los estados posibles
     *
     * @var array
     */
    private const ESTADOS = array(
        'F',
        'R',
        'V',
        'D',
        'VO',
        'FA',
        'TR',
        'L'
    );

    private const BASE_DB = "martinsa_base_prueba.";

    /**
     * Identificador del mandante
     * FIXME esto deberia ser un Objeto del tipo mandante
     *
     * @var int
     */
    private $mandante;

    /**
     * Indentificador del objeto.
     *
     * @var int
     */
    private $id;

    /**
     * Numero de remito asociado.
     *
     * @var int
     */
    private $remito;

    /**
     * Estado en el que se encuentra.
     * Tiene que pertencer a un estado definido en ESTADOS.
     *
     * @var string
     */
    private $estado;

    /**
     * Estado anterior del objeto.
     * Tiene que pertencer a un estado definido en ESTADOS.
     *
     * @var string
     */
    private $estadoAnterior;

    /**
     * Numero de noche.
     *
     * @var int
     */
    private $nronoche;

    /**
     *
     * @var int
     */
    private $bis;

    /**
     * Categoria a la que pertenece el objeto.
     *
     * @var Categorias
     */
    private $categoria;

    /**
     * Titulo del objeto.
     *
     * @var string
     */
    private $titulo;

    /**
     * Descripcion del objeto.
     *
     * @var string
     */
    private $descripcion;

    /**
     * Autor de la obra.
     *
     * @var Autores
     */
    private $autor;

    /**
     * Escuela asociada
     *
     * @var string
     */
    private $escuela;

    /**
     * Tecnica asociada
     *
     * @var string
     */
    private $tecnica;

    /**
     *
     * @var DateTime
     */
    private $fechas;

    /**
     *
     * @var string
     */
    private $firma;

    /**
     *
     * @var string
     */
    private $medidas;

    /**
     *
     * @var string
     */
    private $referencias;

    /**
     *
     * @var bool
     */
    private $aexpertizar;

    /**
     *
     * @var bool
     */
    private $avaluar;

    /**
     *
     * @var bool
     */
    private $suntuario;

    /**
     *
     * @var Precios[]
     */
    private $precios = array();

    /**
     *
     * @var double
     */
    private $comision_ventaonline;

    /**
     *
     * @var double
     */
    private $iva_ventaonline;

    /**
     *
     * @var int
     */
    private $barraOriginal;

    /**
     *
     * @var string
     */
    private $videourl;

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
    {
        $parametros = array();

        $parametros['mandante'] = $this->mandante;
        $parametros['id'] = $this->id;
        $parametros['remito'] = $this->remito;
        $parametros['estado'] = $this->estado;
        $parametros['estadoAnterior'] = $this->estadoAnterior;
        $parametros['nronoche'] = $this->nronoche;
        $parametros['bis'] = $this->bis;
        $parametros['categoria'] = $this->categoria;
        $parametros['titulo'] = addslashes($this->titulo);
        $parametros['descripcion'] = addslashes($this->descripcion);
        $parametros['autor'] = $this->autor;
        $parametros['escuela'] = $this->escuela;
        $parametros['tecnica'] = $this->tecnica;
        $parametros['fechas'] = $this->fechas;
        $parametros['firma'] = $this->firma;
        $parametros['medidas'] = $this->medidas;
        $parametros['referencias'] = $this->referencias;
        $parametros['aexpertizar'] = $this->aexpertizar;
        $parametros['avaluar'] = $this->avaluar;
        $parametros['suntuario'] = $this->suntuario;
        $parametros['precios'] = $this->precios;
        $parametros['comision_ventaonline'] = $this->comision_ventaonline;
        $parametros['iva_ventaonline'] = $this->iva_ventaonline;
        $parametros['barraOriginal'] = $this->barraOriginal;
        $parametros['videourl'] = $this->videourl;

        return $parametros;
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function nuevo(): bool
    {
        $db = DBConnection::getConnection();

        $sql = "INSERT INTO " . self::BASE_DB . "objetos(mandante, id, remito, estado, estadoAnterior, subasta, nronoche, nrolote, bis, categoria, titulo, descripcion, autor, escuela, tecnica, fechas, firma, medidas, referencias, aexpertizar, avaluar, suntuario,  comision_ventaonline, iva_ventaonline, barraOriginal, videourl) VALUES
                                (:mandante, :id, :remito, :estado, :estadoAnterior, :subasta, :nronoche, :nrolote, :bis, :categoria, :titulo, :descripcion, :autor, :escuela, :tecnica, :fechas, :firma, :medidas, :referencias, :aexpertizar, :avaluar, :suntuario, :comision_ventaonline, :iva_ventaonline, :barraOriginal, :videourl)";

        $parametros = array();
        $parametros = $this->jsonSerialize();

        if ($db->query($sql, true, $parametros)) {
            foreach ($this->precios as $precio) {
                $precio->updateThis();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function deleteThis(): bool
    {
        return $this->deleteById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function getById(int $id): object
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function deleteById(int $id): bool
    {}

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function getByThis(): object
    {
        return $this->getById($this->id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
     */
    public function updateThis(): bool
    {
        return $this->updateById($this->id, $this->jsonSerialize());
    }

    /**
     * (non-PHPdoc)
     *
     * @see iModeloStandar::jsonSerialize()
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

        $sql = "UPDATE " . self::BASE_DB . "objetos SET " . $datos . " WHERE id=?";

        $param['id'] = $id;

        if ($db->query($sql, true, $param)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retorna el valor del campo $mandante
     *
     * @return number
     */
    public function getMandante()
    {
        return $this->mandante;
    }

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
     * Retorna el valor del campo $remito
     *
     * @return number
     */
    public function getRemito()
    {
        return $this->remito;
    }

    /**
     * Retorna el valor del campo $estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Retorna el valor del campo $estadoAnterior
     *
     * @return string
     */
    public function getEstadoAnterior()
    {
        return $this->estadoAnterior;
    }

    /**
     * Retorna el valor del campo $nronoche
     *
     * @return number
     */
    public function getNronoche()
    {
        return $this->nronoche;
    }

    /**
     * Retorna el valor del campo $bis
     *
     * @return number
     */
    public function getBis()
    {
        return $this->bis;
    }

    /**
     * Retorna el valor del campo $categoria
     *
     * @return \www\App\Modelo\Categorias
     */
    public function getCategoria()
    {
        return $this->categoria;
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
     * Retorna el valor del campo $descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Retorna el valor del campo $autor
     *
     * @return Autores
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Retorna el valor del campo $escuela
     *
     * @return string
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Retorna el valor del campo $tecnica
     *
     * @return string
     */
    public function getTecnica()
    {
        return $this->tecnica;
    }

    /**
     * Retorna el valor del campo $fechas
     *
     * @return DateTime
     */
    public function getFechas()
    {
        return $this->fechas;
    }

    /**
     * Retorna el valor del campo $firma
     *
     * @return string
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * Retorna el valor del campo $medidas
     *
     * @return string
     */
    public function getMedidas()
    {
        return $this->medidas;
    }

    /**
     * Retorna el valor del campo $referencias
     *
     * @return string
     */
    public function getReferencias()
    {
        return $this->referencias;
    }

    /**
     * Retorna el valor del campo $aexpertizar
     *
     * @return boolean
     */
    public function isAexpertizar()
    {
        return $this->aexpertizar;
    }

    /**
     * Retorna el valor del campo $avaluar
     *
     * @return boolean
     */
    public function isAvaluar()
    {
        return $this->avaluar;
    }

    /**
     * Retorna el valor del campo $suntuario
     *
     * @return boolean
     */
    public function isSuntuario()
    {
        return $this->suntuario;
    }

    /**
     * Retorna el valor del campo $precios
     *
     * @return multitype:\App\Modelo\Precios
     */
    public function getPrecios()
    {
        return $this->precios;
    }

    /**
     * Retorna el valor del campo $comision_ventaonline
     *
     * @return number
     */
    public function getComision_ventaonline()
    {
        return $this->comision_ventaonline;
    }

    /**
     * Retorna el valor del campo $iva_ventaonline
     *
     * @return number
     */
    public function getIva_ventaonline()
    {
        return $this->iva_ventaonline;
    }

    /**
     * Retorna el valor del campo $barraOriginal
     *
     * @return number
     */
    public function getBarraOriginal()
    {
        return $this->barraOriginal;
    }

    /**
     * Retorna el valor del campo $videourl
     *
     * @return string
     */
    public function getVideourl()
    {
        return $this->videourl;
    }

    /**
     * Funcion de carga de datos del parametro $mandante
     *
     * @param number $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
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
     * Funcion de carga de datos del parametro $remito
     *
     * @param number $remito
     */
    public function setRemito($remito)
    {
        $this->remito = $remito;
    }

    /**
     * Funcion de carga de datos del parametro $estado
     *
     * @param string $estado
     */
    public function setEstado($estado)
    {
        if (in_array(trim(strtoupper($estado)), self::ESTADOS)) {
            $this->estado = trim(strtoupper($estado));
        } else {
            throw new Exception("El estado no pertenece a un tipo valido");
        }
    }

    /**
     * Funcion de carga de datos del parametro $estadoAnterior
     *
     * @param string $estadoAnterior
     */
    public function setEstadoAnterior($estadoAnterior)
    {
        if (in_array(trim(strtoupper($estadoAnterior)), self::ESTADOS)) {
            $this->estadoAnterior = trim(strtoupper($estadoAnterior));
        } else {
            throw new Exception("El estado no pertenece a un tipo valido");
        }
    }

    /**
     * Funcion de carga de datos del parametro $nronoche
     *
     * @param number $nronoche
     */
    public function setNronoche($nronoche)
    {
        $this->nronoche = $nronoche;
    }

    /**
     * Funcion de carga de datos del parametro $bis
     *
     * @param number $bis
     */
    public function setBis($bis)
    {
        $this->bis = $bis;
    }

    /**
     * Funcion de carga de datos del parametro $categoria
     *
     * @param \www\App\Modelo\Categorias $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
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
     * Funcion de carga de datos del parametro $descripcion
     *
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Funcion de carga de datos del parametro $autor
     *
     * @param Autores $autor
     */
    public function setAutor($autor)
    {
        $this->autor = $autor;
    }

    /**
     * Funcion de carga de datos del parametro $escuela
     *
     * @param string $escuela
     */
    public function setEscuela($escuela)
    {
        $this->escuela = $escuela;
    }

    /**
     * Funcion de carga de datos del parametro $tecnica
     *
     * @param string $tecnica
     */
    public function setTecnica($tecnica)
    {
        $this->tecnica = $tecnica;
    }

    /**
     * Funcion de carga de datos del parametro $fechas
     *
     * @param DateTime $fechas
     */
    public function setFechas($fechas)
    {
        $this->fechas = $fechas;
    }

    /**
     * Funcion de carga de datos del parametro $firma
     *
     * @param string $firma
     */
    public function setFirma($firma)
    {
        $this->firma = $firma;
    }

    /**
     * Funcion de carga de datos del parametro $medidas
     *
     * @param string $medidas
     */
    public function setMedidas($medidas)
    {
        $this->medidas = $medidas;
    }

    /**
     * Funcion de carga de datos del parametro $referencias
     *
     * @param string $referencias
     */
    public function setReferencias($referencias)
    {
        $this->referencias = $referencias;
    }

    /**
     * Funcion de carga de datos del parametro $aexpertizar
     *
     * @param boolean $aexpertizar
     */
    public function setAexpertizar($aexpertizar)
    {
        $this->aexpertizar = $aexpertizar;
    }

    /**
     * Funcion de carga de datos del parametro $avaluar
     *
     * @param boolean $avaluar
     */
    public function setAvaluar($avaluar)
    {
        $this->avaluar = $avaluar;
    }

    /**
     * Funcion de carga de datos del parametro $suntuario
     *
     * @param boolean $suntuario
     */
    public function setSuntuario($suntuario)
    {
        $this->suntuario = $suntuario;
    }

    /**
     * Funcion de carga de datos del parametro $precios
     *
     * @param multitype:\App\Modelo\Precios $precios
     */
    public function setPrecios($precios)
    {
        $this->precios = $precios;
    }

    /**
     * Funcion de carga de datos del parametro $comision_ventaonline
     *
     * @param number $comision_ventaonline
     */
    public function setComision_ventaonline($comision_ventaonline)
    {
        $this->comision_ventaonline = $comision_ventaonline;
    }

    /**
     * Funcion de carga de datos del parametro $iva_ventaonline
     *
     * @param number $iva_ventaonline
     */
    public function setIva_ventaonline($iva_ventaonline)
    {
        $this->iva_ventaonline = $iva_ventaonline;
    }

    /**
     * Funcion de carga de datos del parametro $barraOriginal
     *
     * @param number $barraOriginal
     */
    public function setBarraOriginal($barraOriginal)
    {
        $this->barraOriginal = $barraOriginal;
    }

    /**
     * Funcion de carga de datos del parametro $videourl
     *
     * @param string $videourl
     */
    public function setVideourl($videourl)
    {
        $this->videourl = $videourl;
    }
}


<?php
namespace www\App\Modelo;

use Exception;
use PDO;
use www\App\Core\DBConnection;

/**
 *
 * @author IVANB
 *        
 */
class Lotes implements iModeloStandar
{

    // private const BASE_DB = "martinsa_base_prueba.";

    // private const BASE_DB_SUBASTAS = "martinsa_subastas_online.";
    private $id;

    private $titulo;

    private $numero;

    private $precios;

    private $lote;

    private $bis;

    private $descripcion;

    private $medidas;

    private $tipo;

    private $nronoche;

    private $categoria;

    private $autor;

    private $escuela;

    private $tecnica;

    private $status;

    private $home;

    private $ordenhome;

    private $mandante;

    private $barra;

    private $ofertas = array();

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

    public function nuevo(): bool
    {}

    public function deleteThis(): bool
    {}

    public function getById(int $id): object
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT * FROM " . self::BASE_DB . "lotes WHERE id = :id_lote";

        $parametros = array();
        $parametros[] = $id;

        $rest = $db->query($sql, true, $parametros);

        if ($fila = $rest->fetchAll()) {

            $this->id = $fila[0]['id'];
            $this->lote = $fila[0]['lote'];
            $this->bis = $fila[0]['bis'];
            $this->titulo = $fila[0]['titulo'];
            $this->descripcion = $fila[0]['descripcion'];
            $this->medidas = $fila[0]['medidas'];
            $this->tipo = $fila[0]['tipo'];
            $this->nronoche = $fila[0]['nronoche'];
            $this->categoria = $fila[0]['categoria'];
            $this->autor = $fila[0]['autor'];
            $this->escuela = $fila[0]['escuela'];
            $this->tecnica = $fila[0]['tecnica'];
            $this->status = $fila[0]['status'];
            $this->home = $fila[0]['home'];
            $this->ordenhome = $fila[0]['ordenhome'];
            $this->mandante = $fila[0]['mandante'];
            $this->barra = $fila[0]['barra'];

            // print_r($fila);

            $this->getPreciosLote();

            $this->ofertas = (new OfertasLote())->getByLote($id);

            return $this;
        } else {
            throw new Exception("La subasta no tiene lotes");
        }
    }

    public function deleteById(int $id): bool
    {}

    public function getByThis(): object
    {}

    public function updateThis(): bool
    {}

    public function updateById(int $id, array $parametros): bool
    {}

    public function getBySubasta(int $idSubasta): array
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT * FROM " . self::BASE_DB . "lotes WHERE subasta = :id_subasta ORDER BY lote";

        $parametros = array();
        $parametros[] = $idSubasta;

        $rest = $db->query($sql, true, $parametros);

        if ($fila = $rest->fetchAll(PDO::FETCH_CLASS, self::class)) {

            foreach ($fila as $lote) {
                $lote->getPreciosLote();
                $lote->setOfertas((new OfertasLote())->getByLote($lote->getId()));
            }
            return $fila;
        } else {
            throw new Exception("La subasta no tiene lotes");
        }
    }

    public function getOfertaGanadora()
    {
        $ganadora = new OfertasLote();
        $ganadora->setImporte(0);

        if (! empty($this->ofertas)) {

            foreach ($this->ofertas as $oferta) {
                if ($oferta->getImporte() > $ganadora->getImporte()) {
                    $ganadora->setImporte($oferta->getImporte());
                }
            }
        }

        return $ganadora;
    }

    public function getNuevaOferta()
    {
        if ($this->getPrecioTipo("Aumento")->getPrecio() > 0) {
            $paso = $this->getPrecioTipo("Aumento")->getPrecio();
        } else {
            $paso = 1;
        }

        if ($this->getOfertaGanadora()->getImporte()) {
            return ($this->getOfertaGanadora()->getImporte() + $paso);
        } else {
            return ($this->getPrecioTipo("Base inicial")->getPrecio() + $paso);
        }
    }

    /**
     * retorna la ruta a la imagen seleccionada del lote
     *
     * @param int $nroFoto
     * @param string $tamanio
     * @return string|boolean
     */
    public function getImagenLote(int $nroFoto = 1, string $tamanio = "grande"): string
    {
        if (strtolower($tamanio) != "grande" && strtolower($tamanio) != "small") {
            throw new Exception("Tamaño de foto no soportado");
        }

        $base = "https://martinsarachaga.com/imagenes_lotes/";
        $ext = "";

        $nombre = $this->id . "_" . $nroFoto . "_" . $tamanio;
        $path = $base . $nombre;

        if (curl_init($path . ".jpg")) {
            $ext = ".jpg";
            return $nombre . $ext;
        } elseif (curl_init($path . ".jpeg")) {
            $ext = "jpeg";
            return $nombre . $ext;
        } elseif (curl_init($path . ".gif")) {
            $ext = "gif";
            return $nombre . $ext;
        }

        if ($ext != "") {
            return $nombre . $ext;
        }

        return $this->getImagenCatalogo();
    }

    public function getImagenCatalogo()
    {
        $base = "https://martinsarachaga.com/imagenes_lotes/";
        $ext = "";

        $nombre = $this->id . "_catalogo";
        $path = $base . $nombre;

        if (curl_init($path . ".jpg")) {
            $ext = ".jpg";
            return $nombre . $ext;
        } elseif (curl_init($path . ".jpeg")) {
            $ext = "jpeg";
            return $nombre . $ext;
        } elseif (curl_init($path . ".gif")) {
            $ext = "gif";
            return $nombre . $ext;
        }

        return "loginimg.jpg";
    }

    public function getPreciosLote()
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT * FROM " . self::BASE_DB . "lotes WHERE id = :id_lote";

        $parametros = array();
        $parametros[] = $this->id;
        $rest = $db->query($sql, true, $parametros);

        if ($fila = $rest->fetchAll()) {
            $fila = $fila[0];

            if ($fila['preciominimo'] > 0) {

                $this->precios[] = new Precios('Base inicial', $fila['moneda'], $fila['preciominimo']);
            } else {
                $this->precios[] = new Precios('Base inicial', 'p', (float) 0);
            }

            if ($fila['preciomaximo'] > 0) {
                $this->precios[] = new Precios('Base', $fila['moneda'], $fila['preciomaximo']);
            } else {
                $this->precios[] = new Precios('Base', 'p', (float) 0);
            }

            if ($fila['preciofijo'] > 0) {
                $this->precios[] = new Precios('Fijo', $fila['moneda'], $fila['preciofijo']);
            } else {
                $this->precios[] = new Precios('Fijo', 'p', (float) 0);
            }

            if ($fila['precioestimativo'] > 0) {
                $this->precios[] = new Precios('Estimativo', $fila['moneda'], $fila['precioestimativo']);
            } else {
                $this->precios[] = new Precios('Estimativo', 'p', (float) 0);
            }

            if ($fila['precioestimativomaximo'] > 0) {
                $this->precios[] = new Precios('Estimativo Maximo', $fila['moneda'], $fila['precioestimativomaximo']);
            } else {
                $this->precios[] = new Precios('Estimativo Maximo', 'p', (float) 0);
            }

            if ($fila['precioaumento'] > 0) {
                $this->precios[] = new Precios('Aumento', $fila['moneda'], $fila['precioaumento']);
            } else {
                $this->precios[] = new Precios('Aumento', 'p', (float) 0);
            }
        } else {
            throw new Exception("El lote no tiene precios");
        }
    }

    public function getPrecioTipo(string $tipo)
    {
        if (is_array($this->precios) || is_object($this->precios)) {
            foreach ($this->precios as $precio) {
                if ($precio->getTipo() == $tipo) {
                    return $precio;
                }
            }
        }
        return NULL;
    }

    /**
     * Retorna el valor del campo $id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retorna el valor del campo $titulo
     *
     * @return mixed
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Retorna el valor del campo $numero
     *
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Retorna el valor del campo $precios
     *
     * @return mixed
     */
    public function getPrecios()
    {
        return $this->precios;
    }

    /**
     * Retorna el valor del campo $lote
     *
     * @return mixed
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Retorna el valor del campo $bis
     *
     * @return mixed
     */
    public function getBis()
    {
        return $this->bis;
    }

    /**
     * Retorna el valor del campo $descripcion
     *
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Retorna el valor del campo $medidas
     *
     * @return mixed
     */
    public function getMedidas()
    {
        return $this->medidas;
    }

    /**
     * Retorna el valor del campo $tipo
     *
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Retorna el valor del campo $nronoche
     *
     * @return mixed
     */
    public function getNronoche()
    {
        return $this->nronoche;
    }

    /**
     * Retorna el valor del campo $categoria
     *
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Retorna el valor del campo $autor
     *
     * @return mixed
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Retorna el valor del campo $escuela
     *
     * @return mixed
     */
    public function getEscuela()
    {
        return $this->escuela;
    }

    /**
     * Retorna el valor del campo $tecnica
     *
     * @return mixed
     */
    public function getTecnica()
    {
        return $this->tecnica;
    }

    /**
     * Retorna el valor del campo $status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Retorna el valor del campo $home
     *
     * @return mixed
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * Retorna el valor del campo $ordenhome
     *
     * @return mixed
     */
    public function getOrdenhome()
    {
        return $this->ordenhome;
    }

    /**
     * Retorna el valor del campo $mandante
     *
     * @return mixed
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Retorna el valor del campo $barra
     *
     * @return mixed
     */
    public function getBarra()
    {
        return $this->barra;
    }

    /**
     * Funcion de carga de datos del parametro $id
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Funcion de carga de datos del parametro $titulo
     *
     * @param mixed $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Funcion de carga de datos del parametro $numero
     *
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Funcion de carga de datos del parametro $precios
     *
     * @param mixed $precios
     */
    public function setPrecios($precios)
    {
        $this->precios = $precios;
    }

    /**
     * Funcion de carga de datos del parametro $lote
     *
     * @param mixed $lote
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
    }

    /**
     * Funcion de carga de datos del parametro $bis
     *
     * @param mixed $bis
     */
    public function setBis($bis)
    {
        $this->bis = $bis;
    }

    /**
     * Funcion de carga de datos del parametro $descripcion
     *
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Funcion de carga de datos del parametro $medidas
     *
     * @param mixed $medidas
     */
    public function setMedidas($medidas)
    {
        $this->medidas = $medidas;
    }

    /**
     * Funcion de carga de datos del parametro $tipo
     *
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Funcion de carga de datos del parametro $nronoche
     *
     * @param mixed $nronoche
     */
    public function setNronoche($nronoche)
    {
        $this->nronoche = $nronoche;
    }

    /**
     * Funcion de carga de datos del parametro $categoria
     *
     * @param mixed $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * Funcion de carga de datos del parametro $autor
     *
     * @param mixed $autor
     */
    public function setAutor($autor)
    {
        $this->autor = $autor;
    }

    /**
     * Funcion de carga de datos del parametro $escuela
     *
     * @param mixed $escuela
     */
    public function setEscuela($escuela)
    {
        $this->escuela = $escuela;
    }

    /**
     * Funcion de carga de datos del parametro $tecnica
     *
     * @param mixed $tecnica
     */
    public function setTecnica($tecnica)
    {
        $this->tecnica = $tecnica;
    }

    /**
     * Funcion de carga de datos del parametro $status
     *
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Funcion de carga de datos del parametro $home
     *
     * @param mixed $home
     */
    public function setHome($home)
    {
        $this->home = $home;
    }

    /**
     * Funcion de carga de datos del parametro $ordenhome
     *
     * @param mixed $ordenhome
     */
    public function setOrdenhome($ordenhome)
    {
        $this->ordenhome = $ordenhome;
    }

    /**
     * Funcion de carga de datos del parametro $mandante
     *
     * @param mixed $mandante
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Funcion de carga de datos del parametro $barra
     *
     * @param mixed $barra
     */
    public function setBarra($barra)
    {
        $this->barra = $barra;
    }

    /**
     * Retorna el valor del campo $ofertas
     *
     * @return OfertasLote[] <\www\App\Modelo\OfertasLote >
     */
    public function getOfertas()
    {
        return $this->ofertas;
    }

    /**
     * Funcion de carga de datos del parametro $ofertas
     *
     * @param
     *            OfertasLote[] <multitype:, array, multitype:\www\App\Modelo\OfertasLote > $ofertas
     */
    public function setOfertas($ofertas)
    {
        $this->ofertas = $ofertas;
    }
}



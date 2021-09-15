<?php
namespace www\App\Modelo;

use Exception;
use www\App\Core\DBConnection;

/**
 *
 * @author IVANB
 *         FIXME Esto esta puesto como parte de la tabla objetos en la base, corresponderia que tenga una tabla independiente.
 */
class Precios implements iModeloStandar
{

    private const BASE_DB = "martinsa_base_prueba.";

    private const TIPOS_PRECIOS = array(
        'Base inicial',
        'Base',
        'Estimativo',
        'Estimativo Maximo',
        'Fijo'
    );

    private const CAMPOS = array(
        'Base inicial' => array(
            'moneda' => "monedapreciobaseinicial",
            'precio' => "preciobaseinicial"
        ),
        'Base' => array(
            'moneda' => "monedapreciobase",
            'precio' => "preciobase"
        ),
        'Estimativo' => array(
            'moneda' => "",
            'precio' => "precioestimativo"
        ),
        'Estimativo Maximo' => array(
            'moneda' => "monedaprecioestimativo",
            'precio' => "precioestimativomaximo"
        ),
        'Fijo' => array(
            'moneda' => "monedapreciofijo",
            'precio' => "preciofijo"
        )
    );

    /**
     * Este va a ser el identificador del precio.
     * Por el momento este no va a ser unico ya va a estar relacionado con el id del objeto.
     *
     * @var int
     */
    private $id;

    /**
     * Establece el tipo de precio.
     * Entre el id y este dato se puede establecer una suerte de clave primaria.
     * Los tipos de precio se establecen en la constante TIPOS_PRECIOS
     *
     * @var string
     */
    private $tipo;

    /**
     * Establece la moneda del precio, los tipos admitidos son 'p' o 'd' en caso contrario la establese como null.
     *
     * @var string
     */
    private $moneda;

    /**
     * Precio en numeros registrado.
     *
     * @var double
     */
    private $precio;

    /**
     */
    public function __construct(string $tipo)
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
     * Recupera el precio por el tipo indicado.
     * El parametro id corresponde al id del objeto.
     *
     * @param int $id
     * @param String $tipo
     * @throws Exception
     * @return self
     */
    public function getByIdTipo(int $id, String $tipo): self
    {
        $db = DBConnection::getConnection();

        $sql = "SELECT " . self::CAMPOS[$tipo]['moneda'] . ", " . self::CAMPOS[$tipo]['precio'] . " FROM " . self::BASE_DB . "objetos WHERE id = ?";

        $parametros = array();
        $parametros[] = $id;

        $rest = $db->query($sql, true, $parametros);

        if ($fila = $db->fetchObject($rest, self::class)) {
            return $fila;
        } else {
            throw new Exception("Precio no encontrado");
        }
    }

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
    {
        // $sql = "UPDATE `objetos` SET `monedapreciobaseinicial`=[value-24] WHERE `id` = ";
    }

    /**
     * Retorna el valor del campo $id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Retorna el valor del campo $tipo
     *
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * Retorna el valor del campo $moneda
     *
     * @return string
     */
    public function getMoneda(): string
    {
        return $this->moneda;
    }

    /**
     * Retorna el valor del campo $precio
     *
     * @return float
     */
    public function getPrecio(): float
    {
        return $this->precio;
    }

    /**
     * Funcion de carga de datos del parametro $id
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Funcion de carga de datos del parametro $tipo
     *
     * @param string $tipo
     */
    public function setTipo(string $tipo)
    {
        if (in_array(trim(strtoupper($tipo)), self::TIPOS_PRECIOS)) {
            $this->tipo = trim(strtoupper($tipo));
        } else {
            throw new Exception("El tipo no pertenece a un tipo valido");
        }
    }

    /**
     * Funcion de carga de datos del parametro $moneda
     *
     * @param string $moneda
     */
    public function setMoneda(string $moneda)
    {
        if (trim(strtolower($moneda)) == "p" || trim(strtolower($moneda)) == "d") {
            $this->moneda = trim(strtolower($moneda));
        } else {
            $this->moneda = NULL;
        }
    }

    /**
     * Funcion de carga de datos del parametro $precio
     *
     * @param double $precio
     */
    public function setPrecio(float $precio)
    {
        $this->precio = $precio;
    }
}


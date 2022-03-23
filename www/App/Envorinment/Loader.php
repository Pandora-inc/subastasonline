<?php
namespace www\App\Envorinment;

/**
 * Class Loader
 *
 * Carga los datos del archivo de entorno.
 *
 * @package millchat\Envorinment
 */
class Loader
{

    /** @var string El nombre del archivo de entorno. */
    private const ENV_FILE = ".env";

    /** @var string La ubicación del .env. */
    private $envPath;

    /** @var array Los datos del entorno. */
    private $data = [];

    /** @var Parser Objeto para parsear el .env. */
    private $parser;

    /**
     * Loader constructor.
     *
     * @param string $envPath
     */
    public function __construct($envPath)
    {
        $this->envPath = $envPath;
        $this->parser = new Parser($this->envPath . DIRECTORY_SEPARATOR . self::ENV_FILE);
        $this->loadEnv();
    }

    /**
     * Carga las variables de entorno del .env a través del Parser.
     */
    private function loadEnv()
    {
        $this->parser->parse();
        $this->data = $this->parser->getContents();
        $this->setEnvVariables();
    }

    /**
     * Setea las variables de entorno.
     */
    private function setEnvVariables()
    {
        // En PHP hay múltiples lugares donde pueden existir variables de entorno de manera nativa, por
        // ejemplo:
        // Las funciones getenv/putenv
        // La súperglobal $_ENV
        // La súperglobal $_SERVER
        // En nuestro caso, vamos a guardar las variables de entorno con putenv/getenv.
        foreach ($this->data as $item) {
            putenv($item);
        }
    }

    /**
     * Retorna el valor del entorno asociado a la $key.
     *
     * @param string $key
     * @return array|false|string
     */
    public function getEnv($key)
    {
        return getenv($key);
    }
}

<?php
namespace www\App\Envorinment;

class Parser
{

    /** @var string La ruta al archivo .env. */
    private $envPath;

    /** @var array Los contenidos del archivo .env ya parseados. */
    private $contents = [];

    /**
     * Parser constructor.
     *
     * @param string $envPath
     */
    public function __construct(string $envPath)
    {
        $this->envPath = $envPath;
    }

    /**
     * Parsea los contenidos del .env en un array.
     */
    public function parse()
    {
        // Leemos el contenido completo del archivo de .env.
        // Como queremos traer cada línea del .env como una posición del array, vamos a explotar el
        // resultado por la constante de php PHP_EOL (php end of line).

        // $this->contents = explode(PHP_EOL, file_get_contents($this->envPath));

        // Filtramos el array para eliminar los items que son vacíos.
        $this->contents = array_filter($this->contents, function ($item) {
            return $item != "";
        });
        $this->contents = array_values($this->contents);
        // echo "<pre>";
        // print_r($this->contents);
        // echo "</pre>";
    }

    /**
     *
     * @return array
     */
    public function getContents()
    {
        return $this->contents;
    }
}

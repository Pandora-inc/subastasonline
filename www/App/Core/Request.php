<?php
namespace www\App\Core;

// FIXME Esto no deberia estar pero tengo un problema con las direcciones de use

/**
 * Class Request
 *
 * @package millchat\Core
 *         
 *          Maneja lo referente a la petición del usuario.
 *          Esto incluye:
 *          - Obtener la ruta pedida.
 *          - Obtener el verbo de la petición.
 */
class Request
{

    /** @var string La ruta buscada a partir de public. */
    protected $requestedUrl;

    /** @var string El verbo de la petición. */
    protected $method;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        // Obtenemos la ruta absoluta que pidió el
        // usuario. Ej:
        // C:/xampp/htdocs/santiago/proyecto/public/peliculas
        // /bin/www/public_html/peliculas
        $rutaAbsoluta = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];

        // Ahora, usamos el path absoluto de public
        // que guardamos previamente en App,
        // y se lo restamos a la ruta pedida por el
        // usuario.
        // Ej:
        // publicPath:
        // C:/xampp/htdocs/santiago/proyecto/public
        // /bin/www/public_html
        // El resultado, nos deja la ruta que
        // registramos.
        $this->requestedUrl = str_replace(App::getPublicPath(), '', $rutaAbsoluta);

        // Limpiamos el query string.
        $inicioQS = strpos($this->requestedUrl, "?");
        if ($inicioQS !== false) {
            $this->requestedUrl = substr($this->requestedUrl, 0, $inicioQS);
        } // :)
    }

    /**
     *
     * @return string
     */
    public function getRequestedUrl()
    {
        return $this->requestedUrl;
    }

    /**
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}

<?php
namespace www;

require_once 'App/Core/config.php';

use Exception;
use www\App\Modelo\Usuarios;

try {

    if ($_POST) {

        $html = "";

        $usuario = new Usuarios();

        $usuario->setNombre($_POST['nombre']);
        $usuario->setApellido($_POST['apellido']);
        $usuario->setEmail($_POST['email']);
        $usuario->setDocumento($_POST['documento']);
        $usuario->setDireccion($_POST['direccion']);
        $usuario->setLocalidad($_POST['localidad']);
        $usuario->setProvincia($_POST['provincia']);
        $usuario->setPais($_POST['pais']);
        $usuario->setCelular($_POST['celular']);
        $usuario->setPrefijo($_POST['prefijo']);
        $usuario->setApodo($_POST['apodo']);
        $usuario->setPassword($_POST['password']);

        if ($usuario->nuevo()) {
            $html .= "<h2>Gracias por crear su cuenta!!!</h2><br>";
        } else {
            $html .= "<h2>No se pudo dar el alta!!!</h2><br>";
        }

        echo $html;
    }
} catch (Exception $e) {

    echo "<div class='alert alert-danger' role='alert'>
    Sucedio un error durante la ejecucion: " . $e->getMessage() . "
    </div>";
}
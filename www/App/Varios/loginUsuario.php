<?php
namespace www\App\Varios;

require_once '/home/martinsa/public_html/www/App/Core/config.php';

if (!function_exists('is_session_started')) {

    //
    function is_session_started()
    {
        if (php_sapi_name() !== 'cli') // Devuelve el tipo de interfaz que hay entre PHP y el servidor
        {
            if (version_compare(phpversion(), '5.4.0', '>=')) // Comparamos la vercion de php
            {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
}

if (is_session_started() === FALSE) {

    session_start();
}

// FIXME esto no va asi pero es un parche

use www\App\Controlers\UsuariosController;
$usr = new UsuariosController();

echo $usr->login();

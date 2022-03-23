<?php
namespace www\App\Core;

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
    if (!headers_sent()) {
        session_start();
    }
}

use www\admin\ClassABM\Sitios;
require_once '/home/martinsa/public_html/autoload.php';

$debug = false;

/*
 *
 */
// DBConnection::setDbHost("localhost");
// DBConnection::setDbUser("martinsa_user_sub_online");
// DBConnection::setDbPass("uqZ1dyV#Zd]q");
// DBConnection::setDbName("martinsa_subastas_online");
// DBConnection::setCharset("utf8");
// DBConnection::setDebug(false);
// DBConnection::setDieOnError(false);
// DBConnection::setMostrarErrores(false);

// Sitios::setDbSever("localhost");
// Sitios::setDbUser("martinsa_user_sub_online");
// Sitios::setDbPass("uqZ1dyV#Zd]q");
// Sitios::setDbBase("martinsa_subastas_online");
// Sitios::setDbCharset("utf8");
// Sitios::setDbTipo("mysql");

DBConnection::setDbHost("70.32.93.127");
DBConnection::setDbUser("admarsar_admin");
DBConnection::setDbPass("mkp16pass20db20mseh");
DBConnection::setDbName("admarsar_sarachaga");
DBConnection::setCharset("utf8");

DBConnection::setDebug($debug);
DBConnection::setDieOnError($debug);
DBConnection::setMostrarErrores($debug);

Sitios::setDbSever("70.32.93.127");
Sitios::setDbUser("admarsar_admin");
Sitios::setDbPass("mkp16pass20db20mseh");
Sitios::setDbBase("admarsar_sarachaga");
Sitios::setDbCharset("utf8");
Sitios::setDbTipo("mysql");

Sitios::setDieOnError($debug);
Sitios::setDebug($debug);
Sitios::setMostrarErrores($debug);

$base_db = "admarsar_sarachaga.";

$base_db_subastas = "admarsar_subastas_online.";
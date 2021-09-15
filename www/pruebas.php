<?php
namespace www;

require_once 'App/Core/config.php';

use www\App\Core\DBConnection;
use www\App\Modelo\Subastas;
$db = DBConnection::getConnection();

$sql = "SELECT * from martinsa_subastas_online.usuarios";
// $sql = "SELECT * from martinsa_subastas_online.usuarios";

if (!$db) {
    die('ERR -> No hay conexión con la BD');
}

$result = $db->query($sql);

$fila = $db->fetch_array($result);

// print_r($sql);
// print_r("|||");
// print_r($fila);
// print_r("|||");

$subasta = new Subastas();
print_r($subasta->getActivas());



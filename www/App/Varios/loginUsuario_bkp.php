<?php
namespace www\App\Varios;
session_start();
// FIXME esto no va asi pero es un parche
require_once '/home/martinsa/public_html/www/App/Core/config.php';

use www\App\Controlers\UsuariosController;
$usr = new UsuariosController();
$data=json_decode($_POST['datosCodificados']);
$usuario= $usr->login($data->email,$data->password);

 
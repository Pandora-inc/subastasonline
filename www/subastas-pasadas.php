<?php
if (!function_exists('is_session_started')) {

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

require_once 'App/Core/config.php';

?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta name="description" content="Subastas online. Registrate y subasta desde tu casa." /><meta name="keywords" content="subastas,subastas online,sarachaga,martin sarachaga,cuadros,arte,obras de arte,lotes,ofertas,galeria,remates,venta online"><meta name="Language" content="Spanish" /><meta name="Title" content="Subastas Pasadas - Martín Sarachaga Subastas Online" /><title>Subastas Pasadas - Martín Sarachaga Subastas Online</title><link rel="stylesheet" href="css/subastas-css.css" /><link rel="stylesheet" href="css/subastas-responsive.css" /><link rel="shortcut icon" href="img/favicon.ico"><link rel="apple-touch-icon" href="img/apple-touch-icon.png"><link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png"><link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"><script src="https://code.jquery.com/jquery-latest.js"></script><script src="js/menu.js"></script></head><body>
	<?
include ("header.php");
?>
<div id="content100" class="contact center cajitaM">		<h2>Subastas Pasadas</h2>		<br>		<h3>Estas son las subastas online realizadas en nuestro sitio y que ya finalizaron.</h3>	</div>	<div id="contenthome" class="caja left"><?php
require_once "content-subastas-pasadas.php";
?>
</div>
<?

include ("inc-news.php");
?>
	<?

include ("footer.php");
?>
</body></html>
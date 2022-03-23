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

?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta name="description" content="Subastas online. Registrate y subasta desde tu casa." /><meta name="keywords" content="subastas,subastas online,sarachaga,martin sarachaga,cuadros,arte,obras de arte,lotes,ofertas,galeria,remates,venta online"><meta name="Language" content="Spanish" /><meta name="Title" content="Contáctanos - Martín Sarachaga Subastas Online" /><title>Contáctanos - Martín Sarachaga Subastas Online</title><link rel="stylesheet" href="css/subastas-css.css" /><link rel="stylesheet" href="css/subastas-responsive.css" /><link rel="shortcut icon" href="img/favicon.ico"><link rel="apple-touch-icon" href="img/apple-touch-icon.png"><link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png"><link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet"><script src="https://code.jquery.com/jquery-latest.js"></script><script src="js/menu.js"></script></head><body>
	<?
include ("header.php");
?>
<div id="content100" class="contact center">		<h2>Contáctanos</h2>		<p class="td ng14">			Rodríguez Peña 1778 (1021) Ciudad Autónoma de Buenos Aires, Argentina</br> online@martinsarachaga.com – Tels. +54 11 4815 0742 / 11 2478 7437		</p>	</div>	<div id="content" class="caja center">		<div class="col1-50">			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3284.5530205360537!2d-58.390570384801464!3d-34.59017488046301!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccabb5bb2ea93%3A0x521d0ba12a7e30f2!2sRodr%C3%ADguez%20Pe%C3%B1a%201778%2C%20C1021%20ABL%2C%20Buenos%20Aires!5e0!3m2!1ses-419!2sar!4v1596547708599!5m2!1ses-419!2sar" width="100%" height="450" frameborder="0" style="border: 0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>		</div>		<div class="col2-50">			<h3>Envianos tu mensaje y lo responderemos a la brevedad.</h3>			<form action='App/Varios/mailContactenos.php'>				<div class="form-row">					<label class="ur-label">Nombre *</label>					<input type="text" class="input-text" name="nombre" id="nombre" placeholder="" value="" required="required">				</div>				<div class="form-row">					<label class="ur-label">Email *</label>					<input type="text" class="input-text" name="email" id="email" placeholder="" value="" required="required">				</div>				<div class="form-row">					<label class="ur-label">Teléfono *</label>					<input type="text" class="input-text" name="telefono" id="telefono" placeholder="" value="" required="required">				</div>				<div class="form-row">					<label class="ur-label">Consulta *</label>					<textarea class="msg" name="consulta" id="consulta"></textarea>				</div>				<div class="left">					<iframe title="reCAPTCHA" src="https://www.google.com/recaptcha/api2/anchor?ar=1&k=6Lf0r6gZAAAAALVP7mCi2S6MIEdbtbbgN2jb6Fws&co=aHR0cHM6Ly9zdWJhc3Rhc29ubGluZS5tYXJ0aW5zYXJhY2hhZ2EuY29tOjQ0Mw..&hl=es&v=Eyd0Dt8h04h7r-D86uAD1JP-&theme=light&size=normal&cb=fwjcrsektjca" width="304" height="78" role="presentation" name="a-sd60q814htzw" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe>				</div>				<div class="form-row">					<button class="btnenviar">ENVIAR</button>				</div>			</form>		</div>	</div>
<?

include ("inc-news.php");

include ("footer.php");
?>
</body></html>
<?php
namespace www;

require_once 'App/Core/config.php';

use Exception;
use www\App\Modelo\Subastas;

try {
    $subasta = (new Subastas())->getById($_REQUEST['id_subasta']);

    $html = "";
    $html .= "<h2>" . $subasta->getTitulo() . "</h2><br>";
    $html .= "<p>EN PESOS ARGENTINOS</p>";
    $html .= "<p class='txt16 subastas'><span class='linkrojo'>Inicio:</span> " . $subasta->getFechainicio() . " | " . $subasta->getHora_inicio() . " hs</p>";
    $html .= "<p class='txt16 subastas'><span class='linkrojo'>Finalización:</span> " . $subasta->getFechafin() . " | " . $subasta->getHora_fin() . " hs</p>";
    $html .= "<h3 class='sep'>Lotes disponibles</h3>";

    // print_r($subasta->getLotes());

    foreach ($subasta->getLotes() as $lote) {
        $html .= " <!-- LOTE -->";
        $html .= "<div class='blockSubasta col5'>";
        $html .= "<a href='detalle-lotes.php?lote=" . $lote->getId() . "' class='btnclass'>";

        $html .= "<div class='objetfit'><img src='/imagenes_lotes/" . $lote->getImagenLote() . "' border='0' title'" . $lote->getTitulo() . "' id='imgsubastas'></div>";

        $html .= "<h4 class='center txt16neg'>LOTE N° " . $lote->getLote() . "</h4>";

        $html .= "<p class='center txt16neg'>Oferta ganadora: " . (($lote->getPrecioTipo("Base inicial"))->getMoneda() == 'p' ? '$ ' : 'u$s ') . $lote->getOfertaGanadora()->getImporte() . "</p>";

        $html .= "<h5 class='center txt16neg'>'" . utf8_encode($lote->getTitulo()) . "'</h5>";

        $html .= "<p class='center sublotes'>Precio base: " . (($lote->getPrecioTipo("Base inicial"))->getMoneda() == 'p' ? '$ ' : 'u$s ') . ($lote->getPrecioTipo("Base inicial"))->getPrecio() . "</p>";
        $html .= "</a>";
        $html .= "<div class='inputOfertar'>";
        $html .= "<input type='text' class='input-text' name='' id='' placeholder='' value='' required='required'>";
        $html .= "</div>";
        $html .= "<div class='inputOfertar'>";
        $html .= "<button class='btnofertar'>OFERTAR</button>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "<!-- FINAL LOTE -->";
    }

    $html .= "<p>Se pueden ver los lotes de lunes a viernes de 12 a 16 horas. Para participar de las subastas es necesario haber creado una cuenta previamente. Recomendamos crear la cuenta con antelación para poder participar en la subasta desde su inicio. Luego de crear la cuenta, deberá iniciar sesión con su usuario y clave para poder ofertar por los lotes. El pago de los lotes deberá efectuarse indefectiblemente dentro de las 96 hs. (días hábiles) de finalizada la subasta. Toda información de saldos y formas de pago las recibirá a la dirección de mail registrada.</p>";

    $html .= "<div class='center'><a href='crear-cuenta.php'><button class='btncuenta'>CREAR CUENTA</button></a></div>";

    echo $html;
} catch (Exception $e) {

    echo "<div class='alert alert-danger' role='alert'>
    Sucedio un error durante la ejecucion: " . $e->getMessage() . "
    </div>";
}
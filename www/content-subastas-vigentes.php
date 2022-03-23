<?php
namespace www;

require_once 'App/Core/config.php';

use Exception;
use www\App\Modelo\Subastas;

try {
    $subastas = (new Subastas())->getActivas();

    $html = "";

    foreach ($subastas as $subasta) {
        if ($subasta->getId() > 0) {
            $html .= "<!-- LOTE -->";
            $html .= "<a href='lotes.php?id_subasta=" . $subasta->getId() . "' class='btnclass'>";
            $html .= "<div class='blockSubasta'>";
            $html .= "<div class='objetfit'><img src='img/" . $subasta->getImagen_nombre() . "' border='0' title='" . $subasta->getTitulo() . "' id='imgsubastas'></div>";
            $html .= "<h4 class='subastas'>" . $subasta->getTitulo() . "</h4>";
            $html .= "<p class='txt16 subastas'><span class='linkrojo'>Inicio:</span> " . $subasta->getFechainicio() . " | " . $subasta->getHora_inicio() . " hs</p>";
            $html .= "<p class='txt16 subastas'><span class='linkrojo'>Finalización:</span> " . $subasta->getFechafin() . " | " . $subasta->getHora_fin() . " hs</p>";
            $html .= "<p class='subastas'>" . $subasta->getDescripcion();
            $html .= "<br><br>";
            $html .= "Se pueden ver los lotes de lunes a viernes de 12 a 16 horas. Para participar de las subastas es necesario haber creado una cuenta previamente. Recomendamos crear la cuenta con antelación para poder participar en la subasta desde su inicio. Luego de crear la cuenta, deberá iniciar sesión con su usuario y clave para poder ofertar por los lotes. El pago de los lotes deberá efectuarse indefectiblemente dentro de las 96 hs. (días hábiles) de finalizada la subasta. Toda información de saldos y formas de pago las recibirá a la dirección de mail registrada.</p>";
            $html .= "<div class='center'>";
            $html .= "<button class='btnenviar'>VER LOS LOTES</button>";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</a>";
            $html .= "<!-- FINAL LOTE -->";
        }
    }

    if ($html == "") {
        $html = "<h2>NO HAY SUBASTAS VIGENTES</h2>";
    }

    echo $html;
} catch (Exception $e) {

    echo "<div class='alert alert-danger' role='alert'>
    Sucedio un error durante la ejecucion: " . $e->getMessage() . "
    </div>";
}
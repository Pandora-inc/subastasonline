<?php
namespace www;

require_once 'App/Core/config.php';

use Exception;
use www\App\Modelo\Subastas;
try {
    $subastas = (new Subastas())->getCerradas();

    $html = "";

    foreach ($subastas as $subasta) {

        $html .= "<!-- LOTE -->";
        $html .= "<a href='lotes.php?id_subasta=" . $subasta->getId() . "' class='btnclass'>";
        $html .= "<div class='blockSubasta col4'>";
        $html .= "<img src='img/" . $subasta->getImagen_nombre() . "' border='0' title'" . $subasta->getTitulo() . "' id='imgsubastas'>";
        $html .= "<h4 class='subastasfinal'>" . $subasta->getTitulo() . "</h4>";
        $html .= "<p class='subastasfinal'> " . $subasta->getFechainicio() . " </p>";
        $html .= "<div class='center'>";
        $html .= "<button class='btnenviar'>VER</button>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</a>";
        $html .= "<!-- FINAL LOTE -->";
    }

    if ($html == "") {
        $html = "<h2>NO HAY SUBASTAS ANTERIORES</h2>";
    }

    echo $html;
} catch (Exception $e) {

    echo "<div class='alert alert-danger' role='alert'>
    Sucedio un error durante la ejecucion: " . $e->getMessage() . "
    </div>";
}
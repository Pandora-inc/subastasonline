<?php
namespace www\admin;

use Exception;
use www\admin\ClassABM\Sitios;
use www\admin\ClassABM\class_abm;
require_once '../App/Core/config.php';

if (isset($_GET['abm_nuevo']) || isset($_GET['abm_editar']) || isset($_GET['abm_borrar']) || isset($_GET['abm_exportar']) || isset($_GET['abm_modif']) || isset($_GET['abm_alta'])) {
    $_SESSION['sMostrar'] = 1;
} else {
    unset($_SESSION['sMostrar']);
    include ("header.php");
}

try {

    $abm = new class_abm();

    $abm->setTabla($base_db_subastas . "ofertas");
    $abm->setRegistros_por_pagina(40);
    $abm->setCampoId("id_subasta");

    $abm->setOrderByPorDefecto("id_subasta DESC");

    $abm->isCampoIdEsEditable(False);
    $abm->setMostrarNuevo(false);
    $abm->setMostrarBorrar(false);
    $abm->setMostrarEditar(false);
    $abm->setBusquedaTotal(true);

    $abm->crearCampoTipo('numero');
    $abm->getUltimoCampo()->setCampo('id_subasta');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('ID');
    $abm->getUltimoCampo()->setCantidadDecimales(0);
    $abm->getUltimoCampo()->setBuscar(false);
    $abm->getUltimoCampo()->setNoEditar(true);
    $abm->getUltimoCampo()->setFormatear(false);
    $abm->getUltimoCampo()->setNoNuevo(true);
    $abm->getUltimoCampo()->setNoEditar(true);

    $abm->crearCampoTipo('numero');
    $abm->getUltimoCampo()->setCampo('id_lote');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Lote');
    $abm->getUltimoCampo()->setBuscar(true);
    $abm->getUltimoCampo()->setCantidadDecimales(0);

    $abm->crearCampoTipo('dbCombo');
    $abm->getUltimoCampo()->setCampo('id_lote');
    $abm->getUltimoCampo()->setTitulo('Lote');
    $abm->getUltimoCampo()->setCampoValor("id");
    $abm->getUltimoCampo()->setCampoTexto("titulo");
    $abm->getUltimoCampo()->setJoinTable($base_db . "lotes");
    $abm->getUltimoCampo()->setJoinCondition("LEFT");
    $abm->getUltimoCampo()->setMostrarValor(true);

    $abm->crearCampoTipo('dbCombo');
    $abm->getUltimoCampo()->setCampo('id_usuario');
    $abm->getUltimoCampo()->setTitulo('Usuario');
    $abm->getUltimoCampo()->setCampoValor("id");
    $abm->getUltimoCampo()->setCampoTexto("email");
    $abm->getUltimoCampo()->setJoinTable($base_db_subastas . "usuarios");
    $abm->getUltimoCampo()->setJoinCondition("LEFT");
    $abm->getUltimoCampo()->setMostrarValor(true);

    $abm->crearCampoTipo('Fecha');
    $abm->getUltimoCampo()->setCampo('fecha');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Fecha');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('numero');
    $abm->getUltimoCampo()->setCampo('importe');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Importe');
    $abm->getUltimoCampo()->setBuscar(true);

    echo "<div id='content' class='caja left'>";
    $abm->generarAbm("", "Ofertas");
    echo "</div>";

    if (!isset($_REQUEST['abm_exportar']) and !isset($_REQUEST['abm_editar']) and !isset($_REQUEST['abm_nuevo'])) {

        // include ("/web/html/inc/footer.php");
    }

    ob_end_flush();
} catch (Exception $e) {
    if (Sitios::isDebug() == true) {
        echo __LINE__ . " - " . __FILE__ . " - " . $e->getMessage();
    } else {
        echo $e->getMessage();
    }

    if (Sitios::isDieOnError() == true) {
        exit();
    }
}
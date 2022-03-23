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

    $abm->setTabla($base_db_subastas . "subastas_online");
    $abm->setRegistros_por_pagina(40);
    $abm->setCampoId("id");

    $abm->setOrderByPorDefecto("id DESC");

    $abm->isCampoIdEsEditable(False);
    $abm->setMostrarNuevo(true);
    $abm->setMostrarBorrar(true);
    $abm->setMostrarEditar(true);
    $abm->setBusquedaTotal(true);

    // $abm->crearCampoTipo('numero');
    // $abm->getUltimoCampo()->setCampo('id');
    // $abm->getUltimoCampo()->setExportar(TRUE);
    // $abm->getUltimoCampo()->setTitulo('ID');
    // $abm->getUltimoCampo()->setCantidadDecimales(0);
    // $abm->getUltimoCampo()->setBuscar(false);
    // $abm->getUltimoCampo()->setNoEditar(true);
    // $abm->getUltimoCampo()->setFormatear(false);
    // $abm->getUltimoCampo()->setNoNuevo(true);
    // $abm->getUltimoCampo()->setNoEditar(true);

    $abm->crearCampoTipo('dbCombo');
    $abm->getUltimoCampo()->setCampo('id');
    $abm->getUltimoCampo()->setTitulo('ID');
    $abm->getUltimoCampo()->setCampoValor("id");
    $abm->getUltimoCampo()->setCampoTexto("id");
    $abm->getUltimoCampo()->setJoinTable($base_db . "subastas");
    $abm->getUltimoCampo()->setJoinCondition("LEFT");
    $abm->getUltimoCampo()->setMostrarValor(true);

    $abm->crearCampoTipo('texto');
    $abm->getUltimoCampo()->setCampo('titulo');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Titulo');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('texto');
    $abm->getUltimoCampo()->setCampo('descripcion');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Descripcion');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('texto');
    $abm->getUltimoCampo()->setCampo('imagen_nombre');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Imagen');
    $abm->getUltimoCampo()->setBuscar(true);


    echo "<div id='content' class='caja left'>";
    $abm->generarAbm("", "Subastas Online");
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

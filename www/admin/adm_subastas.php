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

    $abm->setTabla($base_db . "subastas");
    $abm->setRegistros_por_pagina(40);
    $abm->setCampoId("id");
    $abm->setAdicionalesSelect(" AND subasta_online = 1 ");

    $abm->setOrderByPorDefecto("id DESC");

    $abm->isCampoIdEsEditable(False);
    $abm->setMostrarNuevo(false);
    $abm->setMostrarBorrar(false);
    $abm->setMostrarEditar(false);
    $abm->setBusquedaTotal(true);

    $abm->crearCampoTipo('numero');
    $abm->getUltimoCampo()->setCampo('id');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('ID');
    $abm->getUltimoCampo()->setCantidadDecimales(0);
    $abm->getUltimoCampo()->setBuscar(false);
    $abm->getUltimoCampo()->setNoEditar(true);
    $abm->getUltimoCampo()->setFormatear(false);
    $abm->getUltimoCampo()->setNoNuevo(true);
    $abm->getUltimoCampo()->setNoEditar(true);

    $abm->crearCampoTipo('texto');
    $abm->getUltimoCampo()->setCampo('nro');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('nro');
    $abm->getUltimoCampo()->setBuscar(true);

    // `id``nro`````````
    $abm->crearCampoTipo('fecha');
    $abm->getUltimoCampo()->setCampo('fechainicio');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Fecha de inicio');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('fecha');
    $abm->getUltimoCampo()->setCampo('fechafin');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Fecha de fin');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('bit');
    $abm->getUltimoCampo()->setCampo('cerrada');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Cerrada');
    $abm->getUltimoCampo()->setBuscar(true);
    
    $abm->crearCampoTipo('bit');
    $abm->getUltimoCampo()->setCampo('status');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Estado');
    $abm->getUltimoCampo()->setBuscar(true);
    
    $abm->crearCampoTipo('bit');
    $abm->getUltimoCampo()->setCampo('subasta_online');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Online');
    $abm->getUltimoCampo()->setBuscar(true);

    echo "<div id='content' class='caja left'>";
    $abm->generarAbm("", "Subastas");
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

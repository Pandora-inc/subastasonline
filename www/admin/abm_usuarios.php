<?php
namespace www\admin;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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

    $abm->setTabla($base_db_subastas . "usuarios");
    $abm->setRegistros_por_pagina(40);
    $abm->setCampoId("id");

    $abm->setOrderByPorDefecto("nombre DESC");

    $abm->isCampoIdEsEditable(False);
    $abm->setMostrarNuevo(true);
    $abm->setMostrarBorrar(false);
    $abm->setMostrarEditar(true);
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
    $abm->getUltimoCampo()->setCampo('nombre');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Nombre');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('texto');
    $abm->getUltimoCampo()->setCampo('apellido');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Apellido ');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('texto');
    $abm->getUltimoCampo()->setCampo('apodo');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Apodo ');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('texto');
    $abm->getUltimoCampo()->setCampo('email');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('eMail ');
    $abm->getUltimoCampo()->setBuscar(true);

    $abm->crearCampoTipo('bit');
    $abm->getUltimoCampo()->setCampo('estado');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Estado ');
    $abm->getUltimoCampo()->setBuscar(true);
    $abm->getUltimoCampo()->setTextoBitTrue("Activo");
    $abm->getUltimoCampo()->setTextoBitFalse("Inactivo");

    $abm->crearCampoTipo('bit');
    $abm->getUltimoCampo()->setCampo('admin');
    $abm->getUltimoCampo()->setExportar(TRUE);
    $abm->getUltimoCampo()->setTitulo('Admin');
    $abm->getUltimoCampo()->setBuscar(true);

    echo "<div id='content' class='caja left'>";
    $abm->generarAbm("", "Usuarios");
    echo "</div>";

    if (!isset($_REQUEST['abm_exportar']) and !isset($_REQUEST['abm_editar']) and !isset($_REQUEST['abm_nuevo'])) {

        // include ("/web/html/inc/footer.php");
    }

    // ob_end_flush();
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
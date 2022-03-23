<?php
namespace www;

require_once 'App/Core/config.php';

use www\App\Modelo\Lotes;
use www\App\Modelo\Subastas;
use www\admin\ClassABM\Fechas;
$lote = (new Lotes())->getById($_REQUEST['lote']);
$subasta = (new Subastas())->getByLoteId($_REQUEST['lote']);
// print_r($subasta->getFechainicio());
$diferenciaFecha = Fechas::diferenciaDias($subasta->getFechainicio(), date("Y-m-d", time()));
// print_r($lote);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta name="description" content="Subastas online. Registrate y subasta desde tu casa." />
<meta name="keywords" content="subastas,subastas online,sarachaga,martin sarachaga,cuadros,arte,obras de arte,lotes,ofertas,galeria,remates,venta online">
<meta name="Language" content="Spanish" />
<meta name="Title" content="Titulo del Lote - Martín Sarachaga Subastas Online" />
<title>Titulo del Lote - Martín Sarachaga Subastas Online</title>
<link rel="stylesheet" href="css/subastas-css.css" />
<link rel="stylesheet" href="css/subastas-responsive.css" />
<link rel="shortcut icon" href="img/favicon.ico">
<link rel="apple-touch-icon" href="img/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-latest.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<script src="js/menu.js"></script>
<link rel="stylesheet" href="dist/css/lightbox.min.css">
<script src="dist/js/lightbox-plus-jquery.min.js"></script>
</head>
<body>
	<?

include ("header.php");
?>
<div id="content" class="caja left cajitaM">
		<div class="col2-60">
		<?php

$html = "";
$html .= "<h1>Lote N° " . $lote->getLote() . "</h1>";
$html .= "<br>";
$html .= "<p>Precio base: " . (($lote->getPrecioTipo("Base inicial"))->getMoneda() == 'p' ? '$ ' : 'u$s ') . ($lote->getPrecioTipo("Base inicial"))->getPrecio() . "</p>";
$html .= "<h2>" . utf8_encode($lote->getAutor() . ". " . $lote->getTitulo()) . "</h2>";
$html .= "<p>" . utf8_encode($lote->getDescripcion()) . "</p>";
$html .= "<p class='txt16 subastas'>";
$html .= "	<span class='linkrojo'>Fecha de inicio de la subasta:</span> " . $subasta->getFechainicio() . " | " . $subasta->getHora_inicio() . "hs";
$html .= "</p>";
$html .= "<p class='txt16 subastas'>";
$html .= "	<span class='linkrojo'>Finaliza:</span> " . $subasta->getFechafin() . " | " . $subasta->getHora_fin() . "hs";
$html .= "</p>";

// $diferenciaFecha = strtotime($subasta->getFechainicio()) - strtotime(date("d-m-Y H:i:00", time()));
// print_r($diferenciaFecha);
if ($diferenciaFecha > 0) {
    $html .= "<p class='txt16 subastas linkrojo'>La subasta comienza en " . $diferenciaFecha . " día 12:00:00</p>";
}
$html .= "<p class='txt16 subastas negro'>Precio de base: " . (($lote->getPrecioTipo("Base inicial"))->getMoneda() == 'p' ? '$ ' : 'u$s ') . ($lote->getPrecioTipo("Base inicial"))->getPrecio() . "</p>";

echo $html;

if ($lote->getStatus() && $_SESSION['token'] != null && $diferenciaFecha < 0) {
    ?>

			<div class="inputOfertar">
				<input type="text" class="input-text" name="ofert_<?=$lote->getId()?>" id="ofert_<?=$lote->getId()?>" placeholder="" value="<?=$lote->getNuevaOferta()?>" required="required">
			</div>
			<div class="inputOfertar">
				<button class="btnofertar" onclick='ofertar(<?=$lote->getId()?>);'>OFERTAR</button>
			</div>
<?php
}
?>

			<p>* Para ofertar un importe mayor al incremental predefinido para este lote escribir el monto deseado y hacer clic en OFERTAR</p>
			<div id="historialbox">
				<h3 class="sep">Historial de Subasta</h3>
				<div id="tdtabla">
					<div id="trtabla">
						<p>
							<strong>Fecha</strong>
						</p>
					</div>
					<div id="trtabla">
						<p>
							<strong>Monto</strong>
						</p>
					</div>
					<div id="trtabla">
						<p>
							<strong>Usuario</strong>
						</p>
					</div>
				</div>

				<?php
    $html = "";

    foreach ($lote->getOfertas() as $oferta) {
        $html .= "<!-- HISTORIAL SUBASTA LINEA -->";
        $html .= "<div id='tdtabla'>";
        $html .= "<div id='trtabla'>";
        $html .= "	<p>" . $oferta->getFecha()->format('Y-m-d H:i:s') . "</p>";
        $html .= "</div>";
        $html .= "<div id='trtabla'>";
        $html .= "	<p>" . (($lote->getPrecioTipo("Base inicial"))->getMoneda() == 'p' ? '$ ' : 'u$s ') . $oferta->getImporte() . "</p>";
        $html .= "</div>";
        $html .= "<div id='trtabla'>";
        $html .= "	<p>" . $oferta->getUsuario()->getApodo() . "</p>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "<!-- FIN HISTORIAL SUBASTA LINEA -->";
    }

    echo $html;
    ?>


			</div>
		</div>
		<div class="col1-40">
			<a class="example-image-link" href="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote();?>" data-lightbox="example-set" data-title="Titulo del Lote"> <img src="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote();?>" title="Titulo del Lote" border="0" id="imglotedetalle">
			</a>
			<!-- MENU FOTOS-->
			<div style="margin: 10px 0;">
				<?php

    if ($lote->getImagenLote(1) != "loginimg.jpg") {
        ?><a class="example-image-link" href="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote(1);?>" data-lightbox="example-set" data-title="Titulo del Lote"><img src="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote(1);?>" width="32%"></a><?php
    }
    ?>
				<?php

    if ($lote->getImagenLote(2) != "loginimg.jpg") {
        ?><a class="example-image-link" href="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote(2);?>" data-lightbox="example-set" data-title="Titulo del Lote"><img src="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote(2);?>" width="32%"></a><?php
    }
    ?>
				<?php

    if ($lote->getImagenLote(3) != "loginimg.jpg") {
        ?><a class="example-image-link" href="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote(3);?>" data-lightbox="example-set" data-title="Titulo del Lote"><img src="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote(3);?>" width="32%"></a><?php
    }
    ?>
				<?php

    if ($lote->getImagenLote(4) != "loginimg.jpg") {
        ?><a class="example-image-link" href="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote(4);?>" data-lightbox="example-set" data-title="Titulo del Lote"><img src="https://martinsarachaga.com/imagenes_lotes/<?=$lote->getImagenLote(4);?>" width="32%"></a><?php
    }
    ?>
			</div>
			<!-- FIN MENU FOTOS-->
		</div>
	</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" id="myModal" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
<!--         <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5> -->
<!--         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
<!--           <span aria-hidden="true">&times;</span> -->
<!--         </button> -->
      </div>
      <div class="modal-body">
		<div class="" id="respuesta"></div>
      </div>
      <div class="modal-footer">
<!--         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
<!--         <button type="button" class="btn btn-primary">Close</button> -->
      </div>
    </div>
  </div>
</div>
	<?

include ("footer.php");
?>
<script src="js/lotes.js"></script>
</body>
</html>
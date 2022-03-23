<?php
require_once 'App/Core/config.php';
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta name="description" content="Subastas online. Registrate y subasta desde tu casa." /><meta name="keywords" content="subastas,subastas online,sarachaga,martin sarachaga,cuadros,arte,obras de arte,lotes,ofertas,galeria,remates,venta online"><meta name="Language" content="Spanish" /><meta name="Title" content="Titulo del Lote - Martín Sarachaga Subastas Online" /><title>Titulo del Lote - Martín Sarachaga Subastas Online</title><link rel="stylesheet" href="css/subastas-css.css" /><link rel="stylesheet" href="css/subastas-responsive.css" /><link rel="shortcut icon" href="img/favicon.ico"><link rel="apple-touch-icon" href="img/apple-touch-icon.png"><link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png"><link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"><script src="https://code.jquery.com/jquery-latest.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="js/menu.js"></script></head><body>
	<?
include ("header.php");
?>
<div id="content" class="caja left cajitaM"><?php

require_once 'content-lotes.php';
?>
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
	<?php

include ("footer.php");
?><script src="js/lotes.js"></script></body></html>
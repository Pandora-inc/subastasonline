<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta name="description" content="Subastas online. Registrate y subasta desde tu casa." />
<meta name="keywords" content="subastas,subastas online,sarachaga,martin sarachaga,cuadros,arte,obras de arte,lotes,ofertas,galeria,remates,venta online">
<meta name="Language" content="Spanish"/>
<meta name="Title" content="Martín Sarachaga Subastas Online" />
<title>Martín Sarachaga Subastas Online</title>
<link rel="stylesheet" href="css/subastas-css.css" />
<link rel="stylesheet" href="css/subastas-responsive.css" />
<link rel="shortcut icon" href="img/favicon.ico">
<link rel="apple-touch-icon" href="img/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-latest.js"></script>
<script src="js/menu.js"></script>
<link rel="stylesheet" href="css/sheetslider.min.css"/>
</head>
<body>
	<? include("header.php");?>
	<!--SLIDE-->
<div class="sheetSlider sh-default sh-auto">
   <input id="s1" type="radio" name="slide" checked/> 
   <input id="s2" type="radio" name="slide"/> 
   <input id="s3" type="radio" name="slide"/> 
   <div class="sh__content">
<!-- 1 Slider-->
      <div class="sh__item" id="slide" style="background: url(img/slide-demo.jpg) no-repeat center; background-size: cover;">
      	<a href="#">
         <div class="sh__meta" >
            <!--<img src="img/slide-demo.jpg" id="imgslide">-->
            <div id="sltit">
            	<h1 class="bl">Titulo</h1>
        	</div>
         </div>
     </a>
      </div>
      <!-- Fin 1 Slider-->
<!-- 1 Slider-->
      <div class="sh__item" id="slide" style="background: url(img/slide-demo2.jpg) no-repeat center; background-size: cover;">
      	<a href="#">
         <div class="sh__meta" >
           <!-- <img src="img/slide-demo2.jpg" id="imgslide">-->
            <div id="sltit">
            	<h1 class="bl">Titulo</h1>
        	</div>
         </div>
     </a>
      </div>
      <!-- Fin 1 Slider-->
      <!-- 1 Slider-->
      <div class="sh__item" id="slide" style="background: url(img/demo3.jpg) no-repeat center; background-size: cover;">
      	<a href="#">
         <div class="sh__meta" >
           <!-- <img src="img/slide-demo2.jpg" id="imgslide">-->
            <div id="sltit">
            	<h1 class="bl">Titulo</h1>
        	</div>
         </div>
     </a>
      </div>
      <!-- Fin 1 Slider-->
   </div>
   <!--flechas-->
<div class="sh__arrows">
   <label for="s1"></label>
   <label for="s2"></label>
      <label for="s3"></label>
</div><!-- .sh__arrows -->
</div><!-- SLIDE -->
<div id="contenthome" class="center">
	<h2>REGISTRATE Y OFERTÁ ONLINE DESDE TU CASA</h2>
	<a href="crear-cuenta.php">
	<div class="ichome iccuenta">
		<h3>CREA TU CUENTA</h3>
		<p>Para poder ofertar es necesario estar previamente registrado.</p>
	</div>
	</a>
	<a href="subastas-vigentes.php">
	<div class="ichome iclote">
		<h3>OFERT&Aacute;</h3>
		<p>Seguí cada subasta hasta el último segundo antes del cierre para no perderte los lotes de tu interés.</p>
	</div>
	</a>
	<a href="subastas-vigentes.php">
	<div class="ichome icofertar">
		<h3>ENCONTR&Aacute; TUS LOTES PREFERIDOS</h3>
		<p>Identificá los lotes en los que te interesa ofertar y guardalos en tu lista de favoritos.</p>
	</div>
	</a>
	<a href="contactanos.php">
	<div class="ichome iccontactar">
		<h3>CONTACTANOS</h3>
		<p>Estamos disponibles por cualquier duda.</p>
	</div>
	</a>
	<a href="crear-cuenta.php"><button class="btncuenta">CREAR CUENTA</button></a>
	<a href="preguntas-frecuentes.php"><button class="btninfo">M&Aacute;S INFO</button></a>
	</div>
	<? include("inc-news.php");?>
	<? include("footer.php");?>
		<script src="js/sheetslider.min.js"></script>
</body>
</html>
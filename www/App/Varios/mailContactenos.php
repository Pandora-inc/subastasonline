<?php
$to = "online@martinsarachaga.com";
$subject = "Consulta desde SubastasOnline";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$message = "
<html>
<head>
<title>Consulta desde SubastasOnline</title>
</head>
<body>
<h1>Mensaje de contacto</h1>
<p>
Nombre: " . $_POST['nombre'] . "
Email: " . $_POST['email'] . "
Teléfono: " . $_POST['telefono'] . "
Consulta: " . $_POST['consulta'] . "
</p>
</body>
</html>";

if (mail($to, $subject, $message, $headers)) {
    echo "<p>Gracias por contactearse con nosotros.</p>";
    echo "<a href='https://subastasonline.martinsarachaga.com/www/contactanos.php'>Volver</a>";
}




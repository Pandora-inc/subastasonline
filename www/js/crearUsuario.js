/**
 * 
 */
const $btnEnviar = document.querySelector("#btnEnviar"), 
	$Nombre = document.querySelector("#nombre"), 
	$Apellido = document.querySelector("#apellido"),
	$Email = document.querySelector("#email"),
	$Documento = document.querySelector("#documento"),
	$Direccion = document.querySelector("#direccion"),
	$Localidad = document.querySelector("#localidad"),
	$Provincia = document.querySelector("#provincia"),
	$Pais = document.querySelector("#pais"),
	$Celular = document.querySelector("#celular"),
	$Prefijo = document.querySelector("#prefijo"), 
	$Apodo = document.querySelector("#apodo"), 
	$Password = document.querySelector("#password"), 
	$Conf_password = document.querySelector("#conf_password"), 
	$respuesta = document.querySelector("#respuesta");

// Agregar listener al botón
$btnEnviar.addEventListener("click", () => {
	
	$respuesta.textContent = "Cargando...";
	// Armar objeto con datos
	const datos = {
		nombre: $Nombre.value,
		apellido: $Apellido.value,
		email: $Email.value,
		documento: $Documento.value,
		direccion: $Direccion.value,
		localidad: $Localidad.value,
		provincia: $Provincia.value,
		pais: $Pais.value,
		celular: $Celular.value,
		prefijo: $Prefijo.value,
		apodo: $Apodo.value,
		password: $Password.value,
		conf_password: $Conf_password.value
	};
	// Codificarlo como JSON
	const datosCodificados = JSON.stringify(datos);
	// Enviarlos
	fetch("./App/Varios/crearUsuario.php", {
		method: "POST", // Enviar por POST
		body: datosCodificados, // En el cuerpo van los datos
	})
		.then(respuestaCodificada => respuestaCodificada.json()) // Decodificar JSON que nos responde PHP
		.then(respuestaDecodificada => {
			// Aquí ya tenemos la respuesta lista para ser procesada
			document.querySelector("#crear-usr-form").style.display="none";
			$respuesta.textContent = respuestaDecodificada.data;
		});
});
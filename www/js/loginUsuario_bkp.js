/**
 * 
 */
const $btnEnviar = document.querySelector("#btnEnviar"), 
	$Email = document.querySelector("#mail"), 
	$Password = document.querySelector("#password"), 
	$respuesta = document.querySelector("#respuesta");

// Agregar listener al botón
$btnEnviar.addEventListener("click", () => {
	
	$respuesta.textContent = "Cargando...";
	// Armar objeto con datos
	const datos = {
		email: $Email.value,
		password: $Password.value,
	};
	// Codificarlo como JSON
	const datosCodificados = JSON.stringify(datos);
	// Enviarlos
	fetch("./App/Varios/loginUsuario.php", {
		method: "POST", // Enviar por POST
		body: datosCodificados, // En el cuerpo van los datos
	})
		.then(respuestaCodificada => respuestaCodificada.json()) // Decodificar JSON que nos responde PHP
		.then(respuestaDecodificada => {
			// Aquí ya tenemos la respuesta lista para ser procesada
			document.querySelector("#login-usr-form").style.display="none";
			$respuesta.textContent = respuestaDecodificada.data;
		});
});
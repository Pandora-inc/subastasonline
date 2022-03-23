const $btnEnviar = document.querySelector("#btnEnviar"), 
$Email = document.querySelector("#mail"), 
$Password = document.querySelector("#password"), 
$respuesta = document.querySelector("#respuesta");

$btnEnviar.addEventListener("click", () => {
	$respuesta.textContent = "Cargando..."; const datos = { email: $Email.value, password: $Password.value, }; const datosCodificados = JSON.stringify(datos); fetch("./App/Varios/loginUsuario.php", { method: "POST", body: datosCodificados, }).then(respuestaCodificada => respuestaCodificada.json()).then(respuestaDecodificada => {
		if (respuestaDecodificada.data) {
			document.querySelector("#login-usr-form").style.display = "none"; $respuesta.textContent = respuestaDecodificada.data.usuario.usuario; if (respuestaDecodificada.data.usuario.admin == 1) { window.location = "admin/"; } else {
				window.location = "index.php";
				}
			} else {				$respuesta.textContent = respuestaDecodificada.error;				$('#myModal').modal('show');			}		});});



function ofertar(id_lote) {
	
//	console.log(localStorage.getItem);
	
 	const $respuesta = document.querySelector("#respuesta");
	$respuesta.textContent = "Cargando...";
	
	// Armar objeto con datos
	const datos = {
		lote: id_lote,
		importe: document.querySelector("#ofert_"+id_lote).value,
	};
	// Codificarlo como JSON
	const datosCodificados = JSON.stringify(datos);
	// Enviarlos
	fetch("./App/Varios/ofertar.php", {
		method: "POST", // Enviar por POST
		body: datosCodificados, // En el cuerpo van los datos
	})
		.then(respuestaCodificada => respuestaCodificada.json()) // Decodificar JSON que nos responde PHP
		.then(respuestaDecodificada => {
			// Aquí ya tenemos la respuesta lista para ser procesada
			$respuesta.textContent = respuestaDecodificada.success;
//			$respuesta.classList.add('show');
//			console.log($('#myModal'));
//			$('#myModal').classList.add('show');
//			$('#myModal').modal({
//  				show: true
//			})

			$('#myModal').modal('show');

		});
	
}
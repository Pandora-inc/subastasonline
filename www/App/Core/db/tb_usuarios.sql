CREATE TABLE martinsa_subastas_online.usuarios ( 
	id INT(8) NOT NULL AUTO_INCREMENT COMMENT 'Numero identificador del usuario.' , 
	nombre VARCHAR(50) NOT NULL COMMENT 'Nombre del usuario.' , 
	apellido VARCHAR(50) NOT NULL COMMENT 'Apellido del usuario.' , 
	email VARCHAR(100) NOT NULL COMMENT 'Email asociado a la cuenta de usuario.' , 
	documento VARCHAR(9) NOT NULL COMMENT 'Documento del usuario' , 
	direccion TEXT NOT NULL COMMENT 'Dirección del usuario.' , 
	localidad TEXT NOT NULL COMMENT 'Localidad de la dirección del usuario.' , 
	provincia TEXT NOT NULL COMMENT 'Provincia de la dirección del usuario.' , 
	pais TEXT NOT NULL COMMENT 'Pais de la dirección del usuario.' , 
	celular VARCHAR(15) NOT NULL COMMENT 'Numero de celular del usuario.' , 
	prefijo INT(7) NOT NULL COMMENT 'Prefijo del teléfono del usuario' , 
	apodo VARCHAR(25) NOT NULL COMMENT 'Apodo del usuario (Nickname)' , 
	password TEXT NOT NULL COMMENT 'Contraseña de la cuenta. Debe estar cifrada con password_hash()' , 
	estado BOOLEAN NULL DEFAULT TRUE COMMENT 'Indica si la cuenta de usuario esta activa o no.';
	PRIMARY KEY (id), 
	UNIQUE usr_doc (documento), 
	UNIQUE usr_mail (email), 
	UNIQUE usr_apodo (apodo)
) COMMENT = 'Tabla con la información de las cuentas de usuario.';
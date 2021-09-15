CREATE TABLE martinsa_subastas_online.subastas_online(
    id INT NOT NULL COMMENT 'Identificador de la subasta.',
    titulo VARCHAR(100) NOT NULL COMMENT 'Titulo que se mostrara en la pantalla de la subasta.',
    imagen_nombre VARCHAR(100) NULL COMMENT 'Nombre de la imagen de la portada de la subasta.',
    imagen_link TEXT NULL COMMENT 'En caso de necesitar agregar una imagen que no se encuentre registrada en los servidores.',
    descripcion TEXT NULL COMMENT 'Descripcion de la subasta para incorporar en la pagina.',
    hora_inicio TIME NULL COMMENT 'Hora de inicio de la subasta',
    hora_fin TIME NULL COMMENT 'Hora de finalizacion de la subasta',
    PRIMARY KEY(id)
) COMMENT = 'Tabla con la información adeicional requerida para incorporar en las subastas.';;
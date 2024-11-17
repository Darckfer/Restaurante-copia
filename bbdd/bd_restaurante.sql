drop database if exists `db_restaurante`;

CREATE SCHEMA `db_restaurante` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE db_restaurante;

-- Creacion de tabla roles
CREATE TABLE `db_restaurante`.`roles` (
  `id_rol` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- CREACIÓN TABLA CAMARERO
CREATE TABLE `db_restaurante`.`camarero` (
  `id_camarero` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `usuario` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` INT NOT NULL,
  PRIMARY KEY (`id_camarero`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- CREACIÓN TABLA HISTORIAL
CREATE TABLE `db_restaurante`.`historial` (
  `id_historial` INT NOT NULL AUTO_INCREMENT,
  `id_camarero` INT NOT NULL,
  `id_mesa` INT NOT NULL,
  `hora_inicio` DATETIME NOT NULL,
  `hora_fin` DATETIME NOT NULL,
  PRIMARY KEY (`id_historial`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- CREACIÓN TABLA ESTADO
CREATE TABLE `db_restaurante`.`estados` (
  `id_estado` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- CREACIÓN TABLA MESA
CREATE TABLE `db_restaurante`.`mesa` (
  `id_mesa` INT NOT NULL AUTO_INCREMENT,
  `id_sala` INT NOT NULL,
  `id_estado` INT NOT NULL,
  `num_sillas` INT(2) NOT NULL,
  PRIMARY KEY (`id_mesa`),
  FOREIGN KEY (id_estado) REFERENCES estados(id_estado)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- CREACIÓN TABLA SALA
CREATE TABLE `db_restaurante`.`sala` (
  `id_sala` INT NOT NULL AUTO_INCREMENT,
  `id_tipoSala` INT NOT NULL,
  `nombre_sala` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_sala`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- CREACIÓN TABLA TIPO SALA
CREATE TABLE `db_restaurante`.`tipo_sala` (
  `id_tipoSala` INT NOT NULL AUTO_INCREMENT,
  `tipo_sala` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_tipoSala`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- Creacion de reserva
CREATE TABLE reserva (
  id_reserva INT PRIMARY KEY AUTO_INCREMENT,
  id_mesa INT,
  id_camarero INT,
  num_sillas INT,
  fecha_inicio DATETIME,
  fecha_fin DATETIME,
  FOREIGN KEY (`id_mesa`) REFERENCES mesa(`id_mesa`),
  FOREIGN KEY (`id_camarero`) REFERENCES camarero(`id_camarero`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- CREACION TABLA STOCK
CREATE TABLE `db_restaurante`.`stock` (
  `idStock` INT NOT NULL AUTO_INCREMENT,
  `sillas_stock` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idStock`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- CREACIÓN FOREIGN KEYS
-- Foreign key rol
ALTER TABLE
  `db_restaurante`.`camarero`
ADD
  CONSTRAINT `fk_id_rol` FOREIGN KEY (`rol`) REFERENCES `db_restaurante`.`roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- FOREIGN KEYS TABLA HISTORIAL
ALTER TABLE
  `db_restaurante`.`historial`
ADD
  INDEX `fk_id_camarero_idx` (`id_camarero` ASC) VISIBLE,
ADD
  INDEX `fk_id_mesa_idx` (`id_mesa` ASC) VISIBLE;

;

ALTER TABLE
  `db_restaurante`.`historial`
ADD
  CONSTRAINT `fk_id_camarero` FOREIGN KEY (`id_camarero`) REFERENCES `db_restaurante`.`camarero` (`id_camarero`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD
  CONSTRAINT `fk_id_mesa` FOREIGN KEY (`id_mesa`) REFERENCES `db_restaurante`.`mesa` (`id_mesa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- FOREIGN KEY TABLA MESA
ALTER TABLE
  `db_restaurante`.`mesa`
ADD
  INDEX `fk_id_Sala_idx` (`id_sala` ASC) VISIBLE;

;

ALTER TABLE
  `db_restaurante`.`mesa`
ADD
  CONSTRAINT `fk_id_Sala` FOREIGN KEY (`id_sala`) REFERENCES `db_restaurante`.`sala` (`id_sala`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- FOREIGN KEY TABLA TIPO SALA
ALTER TABLE
  `db_restaurante`.`sala`
ADD
  INDEX `fk_id_tipoSala_idx` (`id_tipoSala` ASC) VISIBLE;

;

ALTER TABLE
  `db_restaurante`.`sala`
ADD
  CONSTRAINT `fk_id_tipoSala` FOREIGN KEY (`id_tipoSala`) REFERENCES `db_restaurante`.`tipo_sala` (`id_tipoSala`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- Insert estados
INSERT INTO
  `estados` (`id_estado`, `nombre`)
VALUES
  (NULL, 'Libre'),
  (NULL, 'Ocupado'),
  (NULL, 'Reservado');

-- Insert roles
INSERT INTO
  `roles` (`id_rol`, `nombre`)
VALUES
  (NULL, 'Encargado'),
  (NULL, 'Camarero');

-- Insert camareros
-- pwd: asdASD123
INSERT INTO
  `camarero` (
    `id_camarero`,
    `nombre`,
    `usuario`,
    `password`,
    `rol`
  )
VALUES
  (
    NULL,
    'Julio',
    'Julio',
    '$2y$10$9YAaDvpj8IDI7WRNVxVq6uYzMnCaUWDGMlU6LS.jv6dgpWcmqcswS',
    1
  ),
  (
    NULL,
    'Marc M',
    'MarcM',
    '$2y$10$9YAaDvpj8IDI7WRNVxVq6uYzMnCaUWDGMlU6LS.jv6dgpWcmqcswS',
    2
  ),
  (
    NULL,
    'Marc C',
    'MarcC',
    '$2y$10$9YAaDvpj8IDI7WRNVxVq6uYzMnCaUWDGMlU6LS.jv6dgpWcmqcswS',
    2
  ),
  (
    NULL,
    'Juanjo',
    'Juanjo',
    '$2y$10$9YAaDvpj8IDI7WRNVxVq6uYzMnCaUWDGMlU6LS.jv6dgpWcmqcswS',
    2
  );

-- Insert tipo sala
INSERT INTO
  `db_restaurante`.`tipo_sala` (`tipo_sala`)
VALUES
  ('Terraza');

INSERT INTO
  `db_restaurante`.`tipo_sala` (`tipo_sala`)
VALUES
  ('Comedor');

INSERT INTO
  `db_restaurante`.`tipo_sala` (`tipo_sala`)
VALUES
  ('Sala privada');

-- Insert salas
INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('1', 'Terraza principal');

INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('1', 'Terraza este');

INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('1', 'Terraza oeste');

INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('2', 'Comedor 1 PB');

INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('2', 'Comedor 2 P1');

INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('3', 'Sala privada PB');

INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('3', 'Sala privada 1 P1');

INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('3', 'Sala privada 2 P1');

INSERT INTO
  `db_restaurante`.`sala` (`id_tipoSala`, `nombre_sala`)
VALUES
  ('3', 'Sala privada 3 P1');

-- Insert mesas
INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('1', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('1', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('1', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('1', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('1', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('2', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('2', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('2', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('2', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('2', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('3', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('3', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('3', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('3', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('3', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('3', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('4', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('4', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('4', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('4', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('4', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('4', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('5', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('5', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('5', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('5', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('5', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('5', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('6', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('6', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('6', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('6', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('7', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('7', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('7', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('7', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('8', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('8', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('8', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('8', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('9', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('9', '4', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('9', '2', 1);

INSERT INTO
  `db_restaurante`.`mesa` (`id_sala`, `num_sillas`, `id_estado`)
VALUES
  ('9', '2', 1);

-- Insert stock
INSERT INTO
  `db_restaurante`.`stock` (`sillas_stock`)
VALUES
  ('30');
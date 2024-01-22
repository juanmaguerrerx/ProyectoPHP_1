
CREATE TABLE operarios (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    admin BOOLEAN DEFAULT 0
);
CREATE TABLE tareas (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nif_cliente VARCHAR(11) NOT NULL,
    nombre_cliente VARCHAR(50) NOT NULL,
    apellidos_cliente VARCHAR(50) NOT NULL,
    telefono_cliente INT(20) NOT NULL,
    correo_cliente VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    poblacion VARCHAR(50) NOT NULL,
    codigo_postal INT(10) NOT NULL,
    provincia VARCHAR(50) NOT NULL,
    estado VARCHAR(1) DEFAULT 'B',
    fecha_creacion DATETIME DEFAULT NOW(),
    operario_id INT NOT NULL AUTO_INCREMENT,
    fecha_realizacion DATETIME,
    anotaciones_anteriores VARCHAR(255),
    anotaciones_posteriores VARCHAR(255),
    FOREIGN KEY (operario_id) REFERENCES operarios(id)
);


-- Insertar datos en la tabla operarios
INSERT INTO operarios (nombre, apellidos, correo, contrasena, admin)
VALUES 
    ('Juan', 'Pérez Gómez', 'juan@email.com', 'clave123', 1),
    ('Admin', 'Administrador', 'admin@root.com', 'root', 1),
    ('María', 'Rodríguez López', 'maria@email.com', 'clave456', 0),
    ('Carlos', 'García Martínez', 'carlos@email.com', 'clave789', 0),
    ('Laura', 'Fernández Ruiz', 'laura@email.com', 'claveabc', 1),
    ('Javier', 'López Torres', 'javier@email.com', 'clavexyz', 0);

-- Insertar datos en la tabla tareas
INSERT INTO tareas (
    nif_cliente,
    nombre_cliente,
    apellidos_cliente,
    telefono_cliente,
    correo_cliente,
    descripcion,
    poblacion,
    codigo_postal,
    provincia,
    estado,
    operario_id,
    fecha_realizacion,
    anotaciones_anteriores,
    anotaciones_posteriores
)
VALUES 
    ('123456789A', 'Cliente1', 'Apellido1', 555123456, 'cliente1@email.com', 'Tarea de reparación', 'Ciudad1', 12345, 'Provincia1', 'A', 1, '2024-01-25', 'Anotación previa', 'Anotación posterior'),
    ('987654321B', 'Cliente2', 'Apellido2', 555234567, 'cliente2@email.com', 'Instalación de software', 'Ciudad2', 54321, 'Provincia2', 'B', 2, NULL, NULL, 'Nuevas anotaciones'),
    ('456789012C', 'Cliente3', 'Apellido3', 555345678, 'cliente3@email.com', 'Mantenimiento preventivo', 'Ciudad3', 67890, 'Provincia3', 'B', 3, '2024-01-20', 'Anotación inicial', 'Detalles adicionales'),
    ('234567890D', 'Cliente4', 'Apellido4', 555456789, 'cliente4@email.com', 'Soporte técnico', 'Ciudad4', 01234, 'Provincia4', 'A', 4, NULL, NULL, 'Observaciones anteriores'),
    ('345678901E', 'Cliente5', 'Apellido5', 555567890, 'cliente5@email.com', 'Actualización de hardware', 'Ciudad5', 56789, 'Provincia5', 'B', 5, '2024-01-21', 'Comentarios previos', 'Comentarios posteriores');



--PROVINCIAS
-- phpMyAdmin SQL Dump
-- version 3.4.4
-- http://www.phpmyadmin.net
--
-- Servidor: 192.168.1.167
-- Tiempo de generación: 23-05-2012 a las 11:13:15
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.6-1+lenny13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP TABLE IF EXISTS `tbl_comunidadesautonomas`;
CREATE TABLE IF NOT EXISTS `tbl_comunidadesautonomas` (
  `id` tinyint(4) NOT NULL DEFAULT '0',
  `nombre` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Afiliados de alta';

-- 
-- Volcar la base de datos para la tabla `tbl_comunidadesautonomas`
-- 

INSERT INTO `tbl_comunidadesautonomas` VALUES (1, 'Andalucía'),
(2, 'Aragón'),
(3, 'Asturias (Principado de)'),
(4, 'Balears (IIles)'),
(5, 'Canarias'),
(6, 'Cantabria'),
(8, 'Castilla y León'),
(7, 'Castilla-La Mancha'),
(9, 'Cataluña'),
(18, 'Ceuta'),
(10, 'Comunidad Valenciana'),
(11, 'Extremadura'),
(12, 'Galicia'),
(13, 'Madrid (Comunidad de)'),
(19, 'Melilla'),
(14, 'Murcia (Región de)'),
(15, 'Navarra (Comunidad Foral de)'),
(16, 'País Vasco'),
(17, 'Rioja (La)');

-- 
-- Estructura de tabla para la tabla `tbl_provincias`
-- 
-- Creación: 13-01-2012 a las 23:11:16
-- 

DROP TABLE IF EXISTS `tbl_provincias`;
CREATE TABLE IF NOT EXISTS `tbl_provincias` (
  `cod` char(2) NOT NULL DEFAULT '00' COMMENT 'Código de la provincia de dos digitos',
  `nombre` varchar(50) NOT NULL DEFAULT '' COMMENT 'Nombre de la provincia',
  `comunidad_id` tinyint(4) NOT NULL COMMENT 'Código de la comunidad a la que pertenece',
  PRIMARY KEY (`cod`),
  KEY `nombre` (`nombre`),
  KEY `FK_ComunidadAutonomaProv` (`comunidad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Provincias de españa; 99 para seleccionar a Nacional';

-- 
-- Volcar la base de datos para la tabla `tbl_provincias`
-- 

INSERT INTO `tbl_provincias` VALUES ('01', 'Alava', 16),
('02', 'Albacete', 7),
('03', 'Alicante', 10),
('04', 'Almera', 1),
('05', 'Avila', 8),
('06', 'Badajoz', 11),
('07', 'Balears (Illes)', 4),
('08', 'Barcelona', 9),
('09', 'Burgos', 8),
('10', 'Cáceres', 11),
('11', 'Cádiz', 1),
('12', 'Castellón', 10),
('13', 'Ciudad Real', 7),
('14', 'Córdoba', 1),
('15', 'Coruña (A)', 12),
('16', 'Cuenca', 7),
('17', 'Girona', 9),
('18', 'Granada', 1),
('19', 'Guadalajara', 7),
('20', 'Guipzcoa', 16),
('21', 'Huelva', 1),
('22', 'Huesca', 2),
('23', 'Jaén', 1),
('24', 'León', 8),
('25', 'Lleida', 9),
('26', 'Rioja (La)', 17),
('27', 'Lugo', 12),
('28', 'Madrid', 13),
('29', 'Málaga', 1),
('30', 'Murcia', 14),
('31', 'Navarra', 15),
('32', 'Ourense', 12),
('33', 'Asturias', 3),
('34', 'Palencia', 8),
('35', 'Palmas (Las)', 5),
('36', 'Pontevedra', 12),
('37', 'Salamanca', 8),
('38', 'Santa Cruz de Tenerife', 5),
('39', 'Cantabria', 6),
('40', 'Segovia', 8),
('41', 'Sevilla', 1),
('42', 'Soria', 8),
('43', 'Tarragona', 9),
('44', 'Teruel', 2),
('45', 'Toledo', 7),
('46', 'Valencia', 10),
('47', 'Valladolid', 8),
('48', 'Vizcaya', 16),
('49', 'Zamora', 8),
('50', 'Zaragoza', 2),
('51', 'Ceuta', 18),
('52', 'Melilla', 19);
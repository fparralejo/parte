-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.16 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4165
-- Date/time:                    2014-12-23 10:21:43
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table calendario.partefpp_claves
DROP TABLE IF EXISTS `partefpp_claves`;
CREATE TABLE IF NOT EXISTS `partefpp_claves` (
  `IdClave` int(10) NOT NULL DEFAULT '0',
  `Id` int(10) DEFAULT NULL,
  `nick` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `borrado` int(5) DEFAULT NULL,
  PRIMARY KEY (`IdClave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table calendario.partefpp_claves: ~0 rows (approximately)
DELETE FROM `partefpp_claves`;
/*!40000 ALTER TABLE `partefpp_claves` DISABLE KEYS */;
INSERT INTO `partefpp_claves` (`IdClave`, `Id`, `nick`, `password`, `borrado`) VALUES
	(1, 1, 'paco', 'paco', 1);
/*!40000 ALTER TABLE `partefpp_claves` ENABLE KEYS */;


-- Dumping structure for table calendario.partefpp_partes
DROP TABLE IF EXISTS `partefpp_partes`;
CREATE TABLE IF NOT EXISTS `partefpp_partes` (
  `IdParte` int(10) NOT NULL DEFAULT '0',
  `Id` int(10) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL COMMENT 'Trabajo,Vacaciones, Baja',
  `descripcion` text,
  `borrado` int(5) DEFAULT NULL,
  PRIMARY KEY (`IdParte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table calendario.partefpp_partes: ~0 rows (approximately)
DELETE FROM `partefpp_partes`;
/*!40000 ALTER TABLE `partefpp_partes` DISABLE KEYS */;
/*!40000 ALTER TABLE `partefpp_partes` ENABLE KEYS */;


-- Dumping structure for table calendario.partefpp_usuarios
DROP TABLE IF EXISTS `partefpp_usuarios`;
CREATE TABLE IF NOT EXISTS `partefpp_usuarios` (
  `Id` int(10) NOT NULL DEFAULT '0',
  `nombre` varchar(50) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `rol` varchar(25) DEFAULT NULL,
  `borrado` int(5) DEFAULT NULL COMMENT '1= dato valido, 0=borrado',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table calendario.partefpp_usuarios: 1 rows
DELETE FROM `partefpp_usuarios`;
/*!40000 ALTER TABLE `partefpp_usuarios` DISABLE KEYS */;
INSERT INTO `partefpp_usuarios` (`Id`, `nombre`, `apellidos`, `fecha`, `rol`, `borrado`) VALUES
	(1, 'paco', 'parralejo parralejo', '2014-12-22 22:05:20', 'Administrador', 1);
/*!40000 ALTER TABLE `partefpp_usuarios` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

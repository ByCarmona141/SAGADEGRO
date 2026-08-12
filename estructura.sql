Enter password: 
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sagadegro
-- ------------------------------------------------------
-- Server version	11.8.8-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `Acceso`
--

DROP TABLE IF EXISTS `Acceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Acceso` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TipoAcceso` int(10) unsigned NOT NULL COMMENT 'Catalogo de tipos de acceso (ssh, http, https, snmp, ssid)',
  `Host` varchar(100) NOT NULL COMMENT 'Host para acceder al dispositivo',
  `Puerto` int(10) unsigned DEFAULT NULL COMMENT 'Puerto para acceder al dispositivo',
  `Usuario` varchar(64) NOT NULL COMMENT 'Usuario para acceder al dispositivo',
  `Password` varchar(64) NOT NULL COMMENT 'Password para acceder al dispositivo',
  `Dispositivo` int(10) unsigned NOT NULL COMMENT 'Dispositivo al que pertenecen los accesos',
  PRIMARY KEY (`id`),
  KEY `fk_Acceso_Dispositivo` (`Dispositivo`),
  KEY `fk_Acceso_TipoAcceso` (`TipoAcceso`),
  CONSTRAINT `fk_Acceso_Dispositivo` FOREIGN KEY (`Dispositivo`) REFERENCES `Dispositivo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Acceso_TipoAcceso` FOREIGN KEY (`TipoAcceso`) REFERENCES `TipoAcceso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Tabla de accesos para los dispositivos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaAcceso`
--

DROP TABLE IF EXISTS `CelaAcceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaAcceso` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `Usuario` int(10) unsigned DEFAULT NULL,
  `Origen` varchar(128) NOT NULL,
  `Tupla` varchar(128) DEFAULT NULL,
  `Acci_on` int(10) unsigned NOT NULL,
  `Datos` text DEFAULT NULL COMMENT 'Datos que se registraron en la accion',
  PRIMARY KEY (`id`),
  KEY `idx_CelaAccesos` (`Acci_on`),
  KEY `idx_CelaAccesos_0` (`Usuario`),
  CONSTRAINT `Fk_CelaAccesos` FOREIGN KEY (`Acci_on`) REFERENCES `CelaAcci_on` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Fk_CelaAccesos_0` FOREIGN KEY (`Usuario`) REFERENCES `CelaUsuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=910 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaAcci_on`
--

DROP TABLE IF EXISTS `CelaAcci_on`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaAcci_on` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Idx_CelaAcci_on` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaCategor_iaConfiguraci_on`
--

DROP TABLE IF EXISTS `CelaCategor_iaConfiguraci_on`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaCategor_iaConfiguraci_on` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NombreCategor_ia` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaComponente`
--

DROP TABLE IF EXISTS `CelaComponente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaComponente` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Componente` varchar(128) NOT NULL,
  `Acci_on` int(10) unsigned DEFAULT NULL,
  `FechaSolicitud` datetime NOT NULL,
  `Descripci_on` varchar(512) NOT NULL,
  `Solicitante` int(10) unsigned NOT NULL,
  `FechaRealizado` datetime DEFAULT NULL,
  `Reviso` int(10) unsigned DEFAULT NULL,
  `Autorizo` int(10) unsigned DEFAULT NULL,
  `Conclusi_on` text DEFAULT NULL,
  `TipoDeComponente` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_CelaSeguimiento_0` (`Solicitante`),
  KEY `idx_CelaSeguimiento_1` (`Reviso`),
  KEY `idx_CelaSeguimiento_2` (`Autorizo`),
  KEY `idx_CelaComponente` (`TipoDeComponente`),
  CONSTRAINT `fk_CelaComponente` FOREIGN KEY (`TipoDeComponente`) REFERENCES `CelaTipoComponente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CelaSeguimiento_0` FOREIGN KEY (`Solicitante`) REFERENCES `CelaUsuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CelaSeguimiento_1` FOREIGN KEY (`Reviso`) REFERENCES `CelaUsuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CelaSeguimiento_2` FOREIGN KEY (`Autorizo`) REFERENCES `CelaUsuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaConfiguraci_on`
--

DROP TABLE IF EXISTS `CelaConfiguraci_on`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaConfiguraci_on` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(128) NOT NULL,
  `Valor` varchar(1024) DEFAULT NULL,
  `Tipo` varchar(20) DEFAULT NULL,
  `Categor_ia` int(10) unsigned NOT NULL,
  `Referencia` varchar(32) DEFAULT NULL,
  `Class` varchar(128) DEFAULT NULL,
  `Code` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Idx_CelaConfiguraci_on_0` (`Nombre`),
  KEY `idx_CelaConfiguraci_on` (`Categor_ia`),
  CONSTRAINT `Fk_CelaConfiguraci_on` FOREIGN KEY (`Categor_ia`) REFERENCES `CelaCategor_iaConfiguraci_on` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaFase`
--

DROP TABLE IF EXISTS `CelaFase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaFase` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Idx_CelaStatusSeguimiento` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaFormulario`
--

DROP TABLE IF EXISTS `CelaFormulario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaFormulario` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(64) NOT NULL,
  `Descripci_on` varchar(128) NOT NULL,
  `Ruta` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Idx_CelaFormulario_0` (`Ruta`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaHistoriaContrase_na`
--

DROP TABLE IF EXISTS `CelaHistoriaContrase_na`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaHistoriaContrase_na` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Usuario` int(10) unsigned NOT NULL,
  `Contrase_na` varchar(512) NOT NULL,
  `UltimoCambio` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Fk_Historial_CelaUsuario_01` (`Usuario`),
  CONSTRAINT `Fk_Historial_CelaUsuario_01` FOREIGN KEY (`Usuario`) REFERENCES `CelaUsuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaIcono`
--

DROP TABLE IF EXISTS `CelaIcono`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaIcono` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(64) DEFAULT NULL,
  `Codigo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=830 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaMen_u`
--

DROP TABLE IF EXISTS `CelaMen_u`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaMen_u` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(64) NOT NULL COMMENT 'Nombre que se muestra en el menú',
  `Descripci_on` varchar(128) DEFAULT NULL,
  `Referencia` varchar(256) NOT NULL COMMENT 'Referencia que enlaza esta opción',
  `Icono` int(10) unsigned NOT NULL,
  `TipoDeElemento` int(10) unsigned NOT NULL,
  `Categor_ia` int(10) unsigned DEFAULT NULL,
  `Prioridad` int(11) NOT NULL,
  `Orientaci_on` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_CelaMe_nu` (`Categor_ia`),
  KEY `idx_CelaMe_nu_0` (`Icono`),
  KEY `idx_CelaMen_u` (`TipoDeElemento`),
  CONSTRAINT `Fk_CelaMen_u` FOREIGN KEY (`TipoDeElemento`) REFERENCES `CelaTipoDeElemento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Fk_CelaMen_u_1` FOREIGN KEY (`Icono`) REFERENCES `CelaIcono` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaOrigen`
--

DROP TABLE IF EXISTS `CelaOrigen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaOrigen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(128) NOT NULL,
  `Tabla` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaPrivilegio`
--

DROP TABLE IF EXISTS `CelaPrivilegio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaPrivilegio` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  `Descripci_on` varchar(512) NOT NULL,
  `Acci_on` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Idx_CelaPrivilegio` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaPrivilegios`
--

DROP TABLE IF EXISTS `CelaPrivilegios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaPrivilegios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Privilegio` int(10) unsigned NOT NULL,
  `Origen` int(10) unsigned NOT NULL COMMENT 'Origen del cual se busca el registro al que se tiene el privilegio',
  `Tupla` int(10) unsigned NOT NULL COMMENT 'Elemento del origen al que se le asigna el privilegio',
  `TuplaAcceso` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_CelaPrivilegios_0` (`Privilegio`),
  KEY `idx_CelaPrivilegios` (`Origen`),
  CONSTRAINT `Fk_CelaPrivilegios` FOREIGN KEY (`Origen`) REFERENCES `CelaOrigen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Fk_CelaPrivilegios_0` FOREIGN KEY (`Privilegio`) REFERENCES `CelaPrivilegio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaProceso`
--

DROP TABLE IF EXISTS `CelaProceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaProceso` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` varchar(8) DEFAULT NULL COMMENT 'identificador del proceso en el servidor',
  `Nombre` varchar(32) NOT NULL,
  `Script` text NOT NULL COMMENT 'Ruta de donde se encuentra el script a ejecutar',
  `Parametros` text DEFAULT NULL COMMENT 'parametros que utilice el script en formato json',
  `Resultado` mediumtext DEFAULT NULL COMMENT 'resultado del proceso. lo que haya devuelto',
  `Periodicidad` enum('year','month','week','days','hours','minute','seconds') DEFAULT NULL COMMENT 'year\nmonth\nweek\ndays\nhours\nminute\nseconds',
  `Recurrente` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Si el proceso debera repetirse',
  `FechaDeInicio` datetime NOT NULL COMMENT 'A partir de cuando inicia el proceso',
  `FechaDeTermino` datetime DEFAULT NULL COMMENT 'Hasta cuando ha de repetirse el proceso',
  `Status` tinyint(4) NOT NULL DEFAULT -1 COMMENT '-2 pausado, -1 Nuevo, 0 en proceso, 1 completado',
  `Periodo` tinyint(3) unsigned DEFAULT NULL COMMENT 'Determina cada cuando se ejecutará el proceso',
  `Original` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci COMMENT='Tabla para almacenar y definir los procesos en segundo plano que se deben realizar';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaRepositorio`
--

DROP TABLE IF EXISTS `CelaRepositorio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaRepositorio` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(512) DEFAULT NULL,
  `Descripci_on` text DEFAULT NULL,
  `Tama_no` decimal(18,6) DEFAULT NULL,
  `Origen` varchar(128) NOT NULL,
  `Tupla` int(10) unsigned NOT NULL,
  `Ruta` varchar(512) NOT NULL,
  `idUsuario` int(10) unsigned NOT NULL,
  `Status` int(10) unsigned NOT NULL,
  `FechaTupla` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_CelaRepositorio` (`idUsuario`),
  KEY `Idx_CelaRepositorio_1` (`Tupla`),
  KEY `Idx_CelaRepositorio_2` (`Origen`),
  KEY `idx_CelaRepositorio_3` (`Status`),
  CONSTRAINT `Fk_CelaRepositorio` FOREIGN KEY (`idUsuario`) REFERENCES `CelaUsuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Fk_CelaRepositorio_0` FOREIGN KEY (`Status`) REFERENCES `CelaStatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaRol`
--

DROP TABLE IF EXISTS `CelaRol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaRol` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  `Siglas` varchar(16) NOT NULL,
  `Descripci_on` varchar(512) DEFAULT NULL,
  `Status` int(10) unsigned NOT NULL DEFAULT 1,
  `Grupo` int(10) unsigned DEFAULT NULL,
  `Tema` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_CelaRol` (`Status`),
  KEY `idx_CelaRol_0` (`Grupo`),
  CONSTRAINT `Fk_CelaRol` FOREIGN KEY (`Status`) REFERENCES `CelaStatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CelaRol_0` FOREIGN KEY (`Grupo`) REFERENCES `CelaRol` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=544 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaSession`
--

DROP TABLE IF EXISTS `CelaSession`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaSession` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Usuario` int(10) unsigned NOT NULL,
  `Cookie` varchar(64) NOT NULL,
  `Nombre` varchar(64) NOT NULL,
  `Valor` text DEFAULT NULL,
  `Tipo` varchar(32) NOT NULL DEFAULT 'int',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20496 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaStatus`
--

DROP TABLE IF EXISTS `CelaStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaStatus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Idx_CelaStatus` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaTema`
--

DROP TABLE IF EXISTS `CelaTema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaTema` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) DEFAULT NULL,
  `Ruta` varchar(512) DEFAULT NULL,
  `Imagen` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaTipoComponente`
--

DROP TABLE IF EXISTS `CelaTipoComponente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaTipoComponente` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaTipoDeElemento`
--

DROP TABLE IF EXISTS `CelaTipoDeElemento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaTipoDeElemento` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaTrazabilidad`
--

DROP TABLE IF EXISTS `CelaTrazabilidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaTrazabilidad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Componente` int(10) unsigned NOT NULL,
  `Fecha` datetime NOT NULL,
  `Fase` tinyint(4) NOT NULL,
  `Programador` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_CelaTrazabilidad` (`Componente`),
  KEY `idx_CelaTrazabilidad_0` (`Fase`),
  KEY `idx_CelaTrazabilidad_1` (`Programador`),
  CONSTRAINT `fk_CelaTrazabilidad` FOREIGN KEY (`Componente`) REFERENCES `CelaComponente` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_CelaTrazabilidad_0` FOREIGN KEY (`Fase`) REFERENCES `CelaFase` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CelaTrazabilidad_1` FOREIGN KEY (`Programador`) REFERENCES `CelaUsuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaUsuario`
--

DROP TABLE IF EXISTS `CelaUsuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaUsuario` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NombreCompleto` varchar(100) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Contrase_na` varchar(100) NOT NULL,
  `CorreoElectr_onico` varchar(100) NOT NULL,
  `Rol` int(10) unsigned NOT NULL,
  `Status` int(10) unsigned NOT NULL,
  `Intento` tinyint(4) NOT NULL DEFAULT 0,
  `LastLogin` datetime DEFAULT NULL,
  `Activo` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Usuario` (`Usuario`),
  KEY `idx_CelaUsuario` (`Rol`),
  KEY `idx_CelaUsuario_0` (`Status`),
  CONSTRAINT `Fk_CelaUsuario` FOREIGN KEY (`Rol`) REFERENCES `CelaRol` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_celausuario_celastatus` FOREIGN KEY (`Status`) REFERENCES `CelaStatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=570 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaWSDL`
--

DROP TABLE IF EXISTS `CelaWSDL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaWSDL` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  `URL` varchar(512) NOT NULL,
  `Usuario` varchar(128) DEFAULT NULL,
  `Contrase_na` varchar(512) DEFAULT NULL,
  `Tipo` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CelaZonaHoraria`
--

DROP TABLE IF EXISTS `CelaZonaHoraria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CelaZonaHoraria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Idx_CelaZonaHoraria` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=580 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Dispositivo`
--

DROP TABLE IF EXISTS `Dispositivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Dispositivo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL COMMENT 'Nombre del dispositivo',
  `MAC` varchar(17) DEFAULT NULL,
  `Modelo` int(10) unsigned NOT NULL,
  `Ubicacion` int(10) unsigned NOT NULL COMMENT 'Ubicacion donde se encuentra el dispositivo',
  `Rack` int(10) unsigned DEFAULT NULL COMMENT 'Rack en el que se ubica el dispositivo en caso de estar en uno',
  `TipoDispositivo` int(10) unsigned NOT NULL COMMENT 'Tipo de Dispositivo',
  `IP` varchar(45) DEFAULT 'DHCP',
  `Serial` varchar(100) DEFAULT NULL COMMENT 'Numero Serial del Dispositivo',
  `Estatus` int(10) unsigned NOT NULL COMMENT 'Estatus del Dispositivo (activo, inactivo, mantenimiento)',
  `Dispositivo` int(10) unsigned DEFAULT NULL COMMENT 'Dispositivo padre al que pertenece',
  PRIMARY KEY (`id`),
  KEY `fk_Dispositivo_Rack` (`Rack`),
  KEY `fk_Dispositivo_Ubicacion` (`Ubicacion`),
  KEY `fk_Dispositivo_TipoDispositivo` (`TipoDispositivo`),
  KEY `fk_Dispositivo_Estatus` (`Estatus`),
  KEY `fk_Dispositivo_Dispositivo` (`Dispositivo`),
  KEY `fk_Dispositivo_Modelo` (`Modelo`),
  CONSTRAINT `fk_Dispositivo_Dispositivo` FOREIGN KEY (`Dispositivo`) REFERENCES `Dispositivo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dispositivo_Estatus` FOREIGN KEY (`Estatus`) REFERENCES `Estatus` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dispositivo_Modelo` FOREIGN KEY (`Modelo`) REFERENCES `Modelo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dispositivo_Rack` FOREIGN KEY (`Rack`) REFERENCES `Rack` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dispositivo_TipoDispositivo` FOREIGN KEY (`TipoDispositivo`) REFERENCES `TipoDispositivo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dispositivo_Ubicacion` FOREIGN KEY (`Ubicacion`) REFERENCES `Ubicacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Tabla de dispositivos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Estatus`
--

DROP TABLE IF EXISTS `Estatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Estatus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Marca`
--

DROP TABLE IF EXISTS `Marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Marca` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Nombre` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Modelo`
--

DROP TABLE IF EXISTS `Modelo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Modelo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Marca` int(10) unsigned NOT NULL,
  `Nombre` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Marca` (`Marca`,`Nombre`),
  CONSTRAINT `fk_Modelo_Marca` FOREIGN KEY (`Marca`) REFERENCES `Marca` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Opci_on`
--

DROP TABLE IF EXISTS `Opci_on`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Opci_on` (
  `id` tinyint(1) NOT NULL,
  `Descripci_on` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Plantilla`
--

DROP TABLE IF EXISTS `Plantilla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Plantilla` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(64) NOT NULL,
  `Descripci_on` varchar(512) DEFAULT NULL,
  `Plantilla` mediumtext NOT NULL,
  `EstaVigente` tinyint(1) NOT NULL DEFAULT 1,
  `TipoPlantilla` tinyint(3) unsigned NOT NULL,
  `Tama_no` enum('Legal','Letter','Tabloid','A4','A3','B5','B4','B3') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_Plantilla` (`EstaVigente`),
  KEY `idx_Plantilla_0` (`TipoPlantilla`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Rack`
--

DROP TABLE IF EXISTS `Rack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Rack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TipoAcceso`
--

DROP TABLE IF EXISTS `TipoAcceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `TipoAcceso` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL COMMENT 'Nombre del catalogo de Tipos de Acceso',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Catalogo de Tipos de Acceso';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TipoDispositivo`
--

DROP TABLE IF EXISTS `TipoDispositivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `TipoDispositivo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  `Icono` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_TipoDispositivo_CelaIcono` (`Icono`),
  CONSTRAINT `fk_TipoDispositivo_CelaIcono` FOREIGN KEY (`Icono`) REFERENCES `CelaIcono` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Catalogo para indicar el tipo de dispositivo';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TipoPlantilla`
--

DROP TABLE IF EXISTS `TipoPlantilla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `TipoPlantilla` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Ubicacion`
--

DROP TABLE IF EXISTS `Ubicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Ubicacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Area` varchar(64) NOT NULL COMMENT 'Nombre del area',
  `Piso` tinyint(3) unsigned DEFAULT NULL COMMENT 'Piso en el que se encuentra la ubicacion',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Tabla de infraestructura de la empresa';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-08-12 17:13:57

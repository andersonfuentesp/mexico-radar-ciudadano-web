DROP TABLE IF EXISTS `estado`;

CREATE TABLE `estado` (
  `EstadoId` int(11) NOT NULL,
  `EstadoGUID` char(36) DEFAULT NULL,
  `EstadoNombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`EstadoId`),
  KEY `UESTADO` (`EstadoNombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Table structure for table `estadopol` */

DROP TABLE IF EXISTS `estadopol`;

CREATE TABLE `estadopol` (
  `EstadoPolId` int(11) NOT NULL,
  `EstadoPolNumero` bigint(20) NOT NULL,
  `EstadoPolPoligono` bigint(20) DEFAULT NULL,
  `EstadoPolChar` mediumtext DEFAULT NULL,
  `EstadoPolKml` mediumtext DEFAULT NULL,
  `EstadoPolGeography` geometry DEFAULT NULL,
  `EstadoPolGeoPolygon` polygon DEFAULT NULL,
  PRIMARY KEY (`EstadoPolId`,`EstadoPolNumero`),
  KEY `UESTADOPOL` (`EstadoPolId`,`EstadoPolNumero`,`EstadoPolPoligono`),
  KEY `UESTADOPOL1` (`EstadoPolId`,`EstadoPolPoligono`),
  CONSTRAINT `IESTADOPOL1` FOREIGN KEY (`EstadoPolId`) REFERENCES `estado` (`EstadoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Table structure for table `EstadoUbi` */

DROP TABLE IF EXISTS `EstadoUbi`;

CREATE TABLE `EstadoUbi` (
  `EstadoUbiId` int(11) NOT NULL,
  `EstadoUbiNumero` bigint(20) NOT NULL,
  `EstadoUbiPoligono` bigint(20) DEFAULT NULL,
  `EstadoUbiLatitud` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `EstadoUbiLongitud` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `EstadoUbiGPS` char(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`EstadoUbiId`,`EstadoUbiNumero`),
  KEY `UESTADOUBI` (`EstadoUbiId`,`EstadoUbiNumero`,`EstadoUbiPoligono`),
  KEY `UESTADOUBI1` (`EstadoUbiId`,`EstadoUbiPoligono`),
  CONSTRAINT `IESTADOUBI1` FOREIGN KEY (`EstadoUbiId`) REFERENCES `estado` (`EstadoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Table structure for table `municipio` */

DROP TABLE IF EXISTS `municipio`;

CREATE TABLE `municipio` (
  `EstadoId` int(11) NOT NULL,
  `MunicipioId` int(11) NOT NULL,
  `MunicipioGuid` char(36) DEFAULT NULL,
  `MunicipioNombre` varchar(40) DEFAULT NULL,
  `MunicipioEsComprometido` tinyint(1) DEFAULT NULL,
  `MunicipioCentroEmergencias` char(20) DEFAULT NULL,
  `MunicipioTipoMapaReporte` smallint(6) DEFAULT NULL,
  `MunicipioZoom` smallint(6) DEFAULT NULL,
  `MunicipioDireccionColonia` char(2) DEFAULT NULL,
  `MunicipioPuntoGPS` char(50) DEFAULT NULL,
  `MunicipioLogo` longblob NOT NULL,
  `MunicipioLogo_GXI` varchar(2048) DEFAULT NULL,
  `MunicipioSerialRedesSociales` mediumint(9) NOT NULL,
  PRIMARY KEY (`EstadoId`,`MunicipioId`),
  KEY `UMUNICIPIO` (`MunicipioNombre`),
  KEY `UMUNICIPIO1` (`MunicipioId`),
  CONSTRAINT `IMUNICIPIO1` FOREIGN KEY (`EstadoId`) REFERENCES `estado` (`EstadoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Table structure for table `municipiopol` */

DROP TABLE IF EXISTS `municipiopol`;

CREATE TABLE `municipiopol` (
  `EstadoPolId` int(11) NOT NULL,
  `MunicipioPolId` int(11) NOT NULL,
  `MunicipioPolNumero` smallint(6) NOT NULL,
  `MunicipioPolPoligono` bigint(20) DEFAULT NULL,
  `MunicipioPolChar` mediumtext DEFAULT NULL,
  `MunicipioPolKml` mediumtext DEFAULT NULL,
  `MunicipioPolGeography` geometry DEFAULT NULL,
  `MunicipioPolGeoPolygon` polygon DEFAULT NULL,
  PRIMARY KEY (`EstadoPolId`,`MunicipioPolId`,`MunicipioPolNumero`),
  KEY `UMUNICIPIOPOL` (`EstadoPolId`,`MunicipioPolId`,`MunicipioPolNumero`,`MunicipioPolPoligono`),
  KEY `UMUNICIPIOPOL1` (`EstadoPolId`,`MunicipioPolId`,`MunicipioPolPoligono`),
  CONSTRAINT `IMUNICIPIOPOL1` FOREIGN KEY (`EstadoPolId`, `MunicipioPolId`) REFERENCES `municipio` (`EstadoId`, `MunicipioId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Table structure for table `MunicipioUbi` */

DROP TABLE IF EXISTS `MunicipioUbi`;

CREATE TABLE `MunicipioUbi` (
  `EstadoUbiId` int(11) NOT NULL,
  `MunicipioUbiId` int(11) NOT NULL,
  `MunicipioUbiNumero` bigint(20) NOT NULL,
  `MunicipioUbiPoligono` bigint(20) DEFAULT NULL,
  `MunicipioUbiLatitud` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `MunicipioUbiLongitud` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `MunicipioUbiGPS` char(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `MunicipioUbiPoint` point DEFAULT NULL,
  PRIMARY KEY (`EstadoUbiId`,`MunicipioUbiId`,`MunicipioUbiNumero`),
  KEY `UMUNICIPIOUBI` (`EstadoUbiId`,`MunicipioUbiId`,`MunicipioUbiNumero`,`MunicipioUbiPoligono`),
  KEY `UMUNICIPIOUBI1` (`EstadoUbiId`,`MunicipioUbiId`,`MunicipioUbiPoligono`),
  CONSTRAINT `IMUNICIPIOUBI1` FOREIGN KEY (`EstadoUbiId`, `MunicipioUbiId`) REFERENCES `Municipio` (`EstadoId`, `MunicipioId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

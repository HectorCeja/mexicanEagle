use ropalindadb;
CREATE TABLE `prospectos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL DEFAULT '',
  `apellidoPaterno` varchar(30) NOT NULL DEFAULT '',
  `apellidoMaterno` varchar(30) NOT NULL DEFAULT '',
  `phone_number` varchar(30) NOT NULL DEFAULT '',
  `pais` varchar(30) NOT NULL DEFAULT '',
  `ciudad` VARCHAR(30) NOT NULL DEFAULT '',
  `username` varchar(250) NOT NULL DEFAULT '',
  `email` varchar(500) NOT NULL DEFAULT '',
  `password` varchar(250) NOT NULL DEFAULT '',
  `authKey` varchar(250) NOT NULL DEFAULT '',
  `estatus` int(1) NOT NULL default 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
drop table prospectos
select * from prospectos
select * from user
truncate table user
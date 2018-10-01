drop table usuarios;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL DEFAULT '',
  `apellido` varchar(250) NOT NULL DEFAULT '',
  `phone_number` varchar(30) NOT NULL DEFAULT '',
  `username` varchar(250) NOT NULL DEFAULT '',
  `email` varchar(500) NOT NULL DEFAULT '',
  `password` varchar(250) NOT NULL DEFAULT '',
  `authKey` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
select * from usuarios;
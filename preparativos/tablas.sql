create table mesas(
	id_mesa int not null auto_increment primary key,
	nombre varchar(50)
) ENGINE = InnoDB;

create table clientes(
	id_cliente int not null auto_increment primary key,
	nombre varchar(50),
	email varchar(90) not null unique key,
	telefono varchar(15)
) ENGINE = InnoDB;

otra version
create table clientes(
	id_cliente int not null auto_increment primary key,
	nombre varchar(50),
	email varchar(90) not null unique key,
	telefono varchar(15)
) ENGINE = InnoDB;


create table reservas(
	id_reserva int not null auto_increment primary key,
	id_mesa int not null,
	id_cliente int not null,
	
	inicio datetime,
	fin datetime,
	identificador varchar(6),
	INDEX fk1(id_mesa),
	INDEX fk2(id_cliente),
	
	FOREIGN KEY fk1(id_mesa) REFERENCES mesas(id_mesa) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY fk2(id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;




MODIFICACIONES
ALTER TABLE  `clientes` ADD UNIQUE (
`email`
)
(ya hecha)


CONSULTAS DE DATOS
Selecciona reservas entre dos momentos
SELECT id_mesa, id_cliente, inicio, fin FROM reservas WHERE inicio BETWEEN '2013-07-25 13:00:01' AND '2013-07-25 16:00:00' 
OR fin BETWEEN '2013-07-25 13:00:00' AND '2013-07-25 16:00:00'



select  m.nombre as nombre_mesa, c.nombre as nombre_cliente, r.inicio, r.fin
from mesas as m, clientes as c, reservas as r
where c.id_cliente = r.id_cliente
and m.id_mesa = r.id_mesa;


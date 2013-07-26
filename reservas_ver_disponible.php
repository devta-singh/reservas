<?php //reservas_ver_disponible.php
ini_set("display_errors", 1);
error_reporting(15);

include_once("inc/funciones.php");

//recibir las variables
if(!isset($_REQUEST["inicio"])){
	//fallo
	$respuesta = array("estado"=>false, 
		"mensaje"=>"No se han recibido variables");
	//devolver la respuesta en json
	echo json_encode($respuesta);
	exit();
}

//le añadimos
$inicio = trim($_REQUEST["inicio"]).":00";



$previa = calcular_fecha($inicio, -6340);

/*$respuesta = array("estado"=>true, 
	"mensaje"=>"El inicio es $inicio y el fin es $fin");
	*/

//conectar a la base de datos
$mysql = new Mysqli("localhost", "root", "root", "m12");

//definimos dos arrays vacios con las mesas y las ocupadas en este intervalo
$mesas = array();
$mesas_ocupadas = array();

//ver las mesas que hay
$sql = "SELECT id_mesa, nombre from mesas";
$res = $mysql->query($sql);
//comprobar que no haya errores de SQL
if($mysql->error != ""){
	//fallo de sql
	$respuesta = array("estado"=>false, 
		"mensaje"=>$mysql->error);
	//devolver la respuesta en json
	echo json_encode($respuesta);
	exit();
}
while($datos = $res->fetch_row()){
	list($id_mesa, $nombre) = $datos;
	//alimento el array de mesas
	$mesas[]=$nombre;
}


//construir una consulta para ver la disponibilidad de las mesas en ese dia y hora


$sql2="SELECT mesas.nombre as nombre, reservas.id_mesa as id_mesa, reservas.id_cliente as id_cliente, reservas.inicio as inicio, reservas.fin as fin 
FROM reservas, mesas
WHERE 
(inicio BETWEEN '$previa' AND '$inicio')
AND mesas.id_mesa = reservas.id_mesa";

$res2 = $mysql->query($sql2);
if($mysql->error != ""){
	//fallo de sql
	$respuesta = array("estado"=>false, 
		"mensaje"=>$mysql->error);
	//devolver la respuesta en json
	echo json_encode($respuesta);
	exit();
}
while($datos2 = $res2->fetch_row()){
	list($nombre, $id_mesa, $id_cliente, $inicio, $fin) = $datos2;
	$mesas_ocupadas[]="$nombre";
}

//construir un array con esos datos
$respuesta = array("estado"=>true, 
		"mensaje"=>"Buscando reservas entre $previa y $inicio",
		"mesas"=>$mesas,
		"mesas_ocupadas"=>$mesas_ocupadas);
		//"mesas_ocupadas"=>array("mesa_1", "mesa_3"));
//devolver la respuesta en json
echo json_encode($respuesta);


?>
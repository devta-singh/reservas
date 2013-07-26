<?php //reservas_crear.php
ini_set("display_errors", 1);
error_reporting(15);

include_once("inc/funciones.php");

//recibir las variables
if(!isset($_REQUEST["fecha"] 
	|| !isset($_REQUEST["hora"]
	|| !isset($_REQUEST["email"]
	|| !isset($_REQUEST["nombre"]
	|| !isset($_REQUEST["mesa"]
)){
	//fallo
	$respuesta = array("estado"=>false, 
		"mensaje"=>"No se han recibido alguna de las variables");
	//devolver la respuesta en json
	echo json_encode($respuesta);
	exit();
}

//tomamos los valores de las variables y las asignamos
$variables=explode(",", "fecha,hora,email,nombre,mesa");
foreach($variables as $variable){
	$$variable = trim($_REQUEST[$variable]);
}
$inicio = $hora.":00";
$fin = calcular_fecha($inicio, 6400);

/*$respuesta = array("estado"=>true, 
	"mensaje"=>"El inicio es $inicio y el fin es $fin");
	*/

//conectar a la base de datos
$mysql = new Mysqli("localhost", "root", "root", "m12");

//primero busco el cliente por el email
$sql="SELECT id_cliente FROM clientes
WHERE email='$email'";
//obtengo su id_cliente
$res = $mysql->query($sql);
if($res->num_rows > 0){
	$datos = $res->fetch_row();
	$id_cliente = $datos["id_cliente"];
}else{
	//si no existe, lo creo y obtengo su id_cliente
	$sql="INSERT INTO clientes
	SET email='$email', nombre='$nombre'";
	//obtengo su id_cliente
	$res = $mysql->query($sql);
	$id_cliente = $mysql->inser_id;
}

//construir una consulta para crear la reserva
$sql2="INSERT INTO reservas SET
	id_cliente = $id_cliente,
	id_mesa = $mesa,
	inicio = '$inicio',
	fin = '$fin'
";
$res2 = $mysql->query($sql2);
if($mysql->error != ""){
	//fallo de sql
	$respuesta = array("estado"=>false, 
		"mensaje"=>$mysql->error);
	//devolver la respuesta en json
	echo json_encode($respuesta);
	exit();
}
$id_reserva = $mysql->insert_id;
$identificador = md5($id_reserva);
$sql3="UPDATE reservas SET identificador='$identificador' WHERE id_reserva=$id_reserva";
$mysql->query($sql3);

$url = "reservas_confirmar.php?r=$identificador";
$enlace = "<a href='$url'>$url</a>";
mail($email, "reserva $identificador", "pincha en esta url para confirmar tu reserva: $enlace", "Content-type: text/html;");

//construir un array con esos datos
$respuesta = array("estado"=>true, 
		"mensaje"=>"reserva creada ($id_reserva) con id: $identificador"
		);
		//"mesas_ocupadas"=>array("mesa_1", "mesa_3"));
//devolver la respuesta en json
echo json_encode($respuesta);


?>
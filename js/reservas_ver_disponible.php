<?php //reservas_ver_disponible.php

//recibir las variables
if(isset($_POST["inicio"])){
	$inicio = $_POST["inicio"];
	$respuesta = array("estado"=>true, 
		"mensaje"=>"El inicio es $inicio");
}else{
	//fallo
	$respuesta = array("estado"=>false, 
		"mensaje"=>"No se han recibido variables");
}

//conectar a la base de datos
//construir una consulta para ver la disponibilidad de las mesas en ese dia y hora
//construir un array con esos datos

//devolver la respuesta en json
echo json_encode($respuesta);
?>
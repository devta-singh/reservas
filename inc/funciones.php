<?php //funciones.php

function calcular_fecha($inicio, $segundos){
	//calculamos el $fin
	//troceamos la fecha y hora a partir del inicio
	list($fecha, $hora) = explode(" ", $inicio);
	//obtenemos anos mes dia a partir de la fecha
	list($ano, $mes, $dia) = explode("-", $fecha);
	//obtenemos h min seg a partir de la hora
	list($h,$m,$s)=explode(":", $hora);
	$tiempo = mktime($h, $m, $s, $mes, $dia, $ano);
	$tiempo_inicio = $tiempo + $segundos;//le sumamos 6400 seg que son 2 horas
	$previa = date("Y-m-d H:i:s", $tiempo_inicio);
	return($previa);
}

?>
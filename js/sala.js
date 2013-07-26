// js/sala.js

var mesas_seleccionadas=new Array;
var la_mesa;
function seleccionar_mesa(){
	//obtengo el id de la mesa que me llama (con click)
	console.log('mesas_seleccionadas antes: ');
	console.log(mesas_seleccionadas);
	var id_mesa = $(this).attr("id");
	console.log('id_mesa: '+id_mesa);
	//averiguo si esta seleccionada
	if($(this).hasClass("seleccionada")){
		//estoy seleccionando esta mesa
		//alert("estaba seleccionada");
		//borro este elemento del array de mesas seleccionadas		
		//mesas_seleccionadas[id_mesa]=null;
		delete mesas_seleccionadas[id_mesa];
	}else{
		//saco de la seleccion esta mesa
		//alert("estaba libre, la seleccionamos");
		//añado esta mesa al array de mesas seleccionadas
		mesas_seleccionadas[id_mesa]=id_mesa;
		la_mesa=id_mesa;
		$("#datos_extra").fadeIn(2000);		
	}
	$(this).toggleClass("seleccionada");
	//comentario en la consola
	console.log('mesas_seleccionadas despues: ');
	console.log(mesas_seleccionadas);
}

function reservas_ver_disponible(){
	//obtenemos los datos de día y hora
	console.log("mirando reservas");
	var dia, hora, inicio;
	dia = $("#dia").val();
	hora = $("#inicio").val();
	inicio = dia+"+"+hora;
	//esto emula el formato mysql para datetime
	//2013-07-24 23:20:00

	

	//hacemos la llamada por ajax al reservas_ver_disponible.php
	var url, datos, r;
	r = Math.random();
	url="reservas_ver_disponible.php?r="+r;
	datos="inicio="+inicio;
	console.log(" url: "+url+" datos: "+datos);
	
	$.post(url, datos, function(datos_devueltos){
		console.log(datos_devueltos);

		var mesas = datos_devueltos.mesas;
		var mesas_ocupadas = datos_devueltos.mesas_ocupadas;
		//recorrer este array y pintar como ocupadas
		//las que estén en este array
		var i,mesa;
		for(i in mesas){
			//console.log("i: "+i);
			mesa = mesas[i];
			//console.log(mesa);
			//#mesa_1 img
			$("#"+mesa+" img").attr("src", "img/mesa_libre.png");
		}
		var ocupadas="";
		//ahora pimto las mesas ocupadas
		for(i in mesas_ocupadas){
			mesa = mesas_ocupadas[i];
			ocupadas+= i+" "+mesa+" ";
			$("#"+mesa+" img").attr("src", "img/mesa_ocupada.png");
		}		
		console.log(ocupadas);

	}, "json");

	//alert("viendo mesas disponibles");
	$("ul#mesas").show();
}



//comprobacion para la fecha y hora de la reserva
//para mostrar las mesas disponibles
var dia_listo = false;
var hora_listo = false;

function comprobar_paso2(){
	if(dia_listo==true && hora_listo==true){
		reservas_ver_disponible();
	}
}

function control_dia(){
	dia_listo=true;
	console.log("dia_listo - true "+dia_listo);
	comprobar_paso2();
}

function control_hora(){
	console.log("hora_listo");
	
	if($(this).val()!=""){
		hora_listo=true; 
		comprobar_paso2();
	}else{
		hora_listo=false;
	}	
}


//crear reserva
function reservas_anadir(){
	//recojo los datos
	var fecha, hora, nombre, email, mesa;
	fecha = $("#dia").val();
	hora = $("#inicio").val();
	email = $("#email").val();
	nombre = $("#nombre_reserva").val();
	mesa = la_mesa;

	var url, datos, r;
	r = Math.random();
	url = "reservas_crear.php?r="+r;
	datos="fecha="+fecha+"&hora="+hora+"&email="+email+"&nombre="+nombre+"&mesa="+mesa;

	//compongo las variables para la llamada
	$.post(url, datos, function(datos_devueltos){
		console.log(datos_devueltos);
	}, "json");

	//hago la llamada (AJAX) y observo el resultado
}

//comprobacion para mostrar el boton reservar
var email_listo = false;
var nombre_listo = false;
function comprobar_paso3(){
	if(email_listo==true && nombre_listo==true){
		reservas_anadir();
	}
}
function control_email(){
	if($(this).val()!=""){
		email_listo=true; 
		comprobar_paso3();
	}else{
		email_listo=false;
	}
	console.log("email_listo - "+email_listo);
}
function control_nombre(){
	console.log("nombre_listo");
	if($(this).val()!=""){
		nombre_listo=true; 
		comprobar_paso3();
	}else{
		nombre_listo=false;
	}	
	console.log("nombre_listo - "+nombre_listo);
}

$(document).ready(function(){
	$("ul#mesas").hide();//oculto la sala con las mesas
	$("#datos_extra").hide();//oculto el email y el boton reservar
	$("#btn_reservar").hide();
	$("#email").on("change", control_email);
	$("#nombre_reserva").on("change", control_nombre);

	$("ul#mesas li").on("click", seleccionar_mesa);
	//$("#mesa_1").on("click", seleccionar_mesa);
	//$("#mesa_2").on("click", seleccionar_mesa);

	$("#dia").on("change", control_dia);
	$("#inicio").on("change", control_hora);





	$("#btn_disponiblilidad").hide();

	$("#btn_disponiblilidad").on("click", reservas_ver_disponible);

});




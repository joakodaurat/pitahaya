

function agregarInversion(){
	var	concepto = $('#input_concepto').val();
	var monto = $('#input_monto').val();
	if (concepto && monto) {
		$('#botonCargarInversion').css("cursor","not-allowed");

		$.ajax({
			url: 'ajax_addInversion.php',
			type: 'POST',
			data: {concepto : concepto, monto : monto},
			dataType: 'json'
		}).done(function(data){
			$('#input_concepto').val("");
			$('#input_monto').val("");
			$('#botonCargarInversion').css("cursor","pointer");
			cargar_inversion_a_tabla(concepto,monto,data);
		}).fail(function(xhr, textStatus, errorThrown) {
			$('#input_concepto').val("");
			$('#input_monto').val("");
			 alert('No se puedo cargar la inversion, intentelo nuevamente');
			console.log(xhr.responseText);
		});

	} else {
		 alert('Debe ingresar un concepto y un monto');
	}

}

function borrar_inversion(id_delete_inversion){

	var opcion = confirm("¿Desea borrar la inversion?");
   
    if (opcion == true) {

       		$.ajax({
			url: 'ajax_deleteinversion.php',
			type: 'POST',
			data: {id_delete_inversion : id_delete_inversion},
			dataType: 'json'
		}).done(function(data){
			  borrar_de_tabla(data);
			  calcular_total();
		}).fail(function(xhr, textStatus, errorThrown) {
			 alert('No se puedo eliminar la inversion');
			console.log(xhr.responseText);
		});

	} 

}

function cargar_inversion_a_tabla (concepto,monto,id) {

  var newRowContent = '<tr id='+id+'>';
  newRowContent += '<td>'+concepto+'</td>';
  newRowContent += '<td>$<span class="monto_inversion">'+monto+'</span></td>';
  newRowContent += '<td onclick="borrar_inversion('+id+')" ><a href="#"><i style="color:red" class="fa fa-trash" aria-hidden="true"></i></a></td>';
  newRowContent += '</tr>';

  $("#cuerpodetabla").append(newRowContent);
  
  calcular_total();
}

function borrar_de_tabla(id) {
 $('#'+id).remove();
}
function calcular_total() {
  var sum = 0;
  $('.monto_inversion').each(function() {
    $val = parseInt($(this).text());

    if ($val != ''){ sum += parseFloat($val);}
  });
  $('#monto_total').html(sum);
}
$('#combo_vendedor').on('select2:select', function (e) {
	let searchParams = new URLSearchParams(window.location.search)
    var data = e.params.data;

   
      var fecha_desde = searchParams.get('fecha_desde');
    var fecha_hasta = searchParams.get('fecha_hasta');
    if(fecha_desde){location.href = "../ventas/ventas.php?vendedor="+data.id+"&filtro="+data.text+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta;}else{
    	 location.href = "../ventas/ventas.php?vendedor="+data.id+"&filtro="+data.text;
    }
});

$('#combo_marca').on('select2:select', function (e) {
	let searchParams = new URLSearchParams(window.location.search)
    var data = e.params.data;
    var fecha_desde = searchParams.get('fecha_desde');
    var fecha_hasta = searchParams.get('fecha_hasta');
    if(fecha_desde){location.href = "../ventas/ventas.php?marca="+data.id+"&filtro="+data.text+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta;}else{
    	location.href = "../ventas/ventas.php?marca="+data.id+"&filtro="+data.text;
    }
    
});

function cargarselect() {
	let searchParams = new URLSearchParams(window.location.search)
	if (searchParams.has('vendedor')){
		var idvendedor = searchParams.get('vendedor');
		$("#combo_vendedor").select2();
		$("#combo_vendedor").val(idvendedor).trigger("change");
	}
	if (searchParams.has('marca')){
		var idmarca = searchParams.get('marca');
		$("#combo_marca").select2();
		$("#combo_marca").val(idmarca).trigger("change");
	}
	if (searchParams.has('filtro')){
	var filtro = searchParams.get('filtro');
	$("#porfiltro").html("de "+filtro);
      }
}

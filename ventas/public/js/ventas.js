$('#combo_vendedor').on('select2:select', function (e) {
    var data = e.params.data;

    location.href = "../ventas/ventas.php?vendedor="+data.id;
});

$('#combo_marca').on('select2:select', function (e) {
    var data = e.params.data;

    location.href = "../ventas/ventas.php?marca="+data.id;
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
}

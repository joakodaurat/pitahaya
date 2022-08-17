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

$(document).ready(function() {


            $('#fecha_form').daterangepicker({
                "locale": {
                    "format": "DD/MM/YYYY",
                    "separator": " - ",
                    "applyLabel": "Aplicar",
                    "cancelLabel": "Cancelar",
                    "fromLabel": "Desde",
                    "toLabel": "Hasta",
                    "customRangeLabel": "Personalizar",
                    "daysOfWeek": [
                        "Do",
                        "Lu",
                        "Ma",
                        "Mi",
                        "Ju",
                        "Vi",
                        "Sa"
                    ],
                    "monthNames": [
                        "Enero",
                        "Febrero",
                        "Marzo",
                        "Abril",
                        "Mayo",
                        "Junio",
                        "Julio",
                        "Agosto",
                        "Septiembre",
                        "Octubre",
                        "Noviembre",
                        "Diciembre"
                    ],
                    "firstDay": 1
                }
            });
            $('#fecha_form').on('apply.daterangepicker', function(ev, picker) {
              $('#fecha_desde').val(picker.startDate.format('YYYY-MM-DD'));
              $('#fecha_hasta').val(picker.endDate.format('YYYY-MM-DD'));
              $('#form-fecha').submit();
            });


            $('#tabla_tickets').DataTable(
              {

              	        "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
            /*
             Calculo el total cuando se cambia en el buscador
             */
            var iPageMarket = 0;
            for ( var i=iStart ; i<iEnd ; i++ )
            {
                iPageMarket += aaData[ aiDisplay[i] ][1]*1;
            }

            var total = iPageMarket;
            $("#totalCalculado").html(total);

        },


                "pageLength": 50,
                "aaSorting": [0,'desc'],
                "oSearch": {"sSearch": ""},
                "dom": 'Bfrtip',
                  "buttons": [
                    'copy', 'excel', 'pdf'
                    ]
              });
            $('.dt-buttons').css('text-align', 'left')
            $('#tabla_tickets_filter > label').css('color', 'black')

            $('#combo_vendedor').select2({
              language: {
                noResults: function (params) {
                  return "No hay resultados.";
                }
              }
            });
            $('#combo_marca').select2({
              language: {
                noResults: function (params) {
                  return "No hay resultados.";
                }
              }
            });

             $('.select2-container').css('width', '100%');

             cargarselect();
            


        } );



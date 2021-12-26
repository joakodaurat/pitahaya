
/* -- FUNCIONES NUEVA VENTA -- */
/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

/* Alta de cliente con Ajax */
function ajax_guardarCliente() {

  var data = JSON.stringify( $('#detalle_cliente_add').serializeArray() ); 
  
    $.ajax({
      url: 'ajax_addCliente.php',
      type: 'POST',
      data: {data : data},
      dataType: 'json'
  }).done(function(data){
      //console.log(data.id);
      var newOption = new Option(data.nombre, data.id, false, false);
      $('#combo_cli_cobro').append(newOption).trigger('change');
      $('#combo_cli_cobro').val(data.id);
      $('#myModalClienteAdd').modal('hide');
      getEstadoCC(data.id);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}

/* Calcular montos totales */
function calcular_total() {
  var sum = 0;
  $('.precio_parc').each(function() {
      sum += parseFloat($(this).val());
  });
  $('#saldo_final_total').val(sum);

// sumo los totales sin tener en cuenta los descuentos
  var sum1 = 0;
  $('.precio_parc_sindescuento').each(function() {
      sum1 += parseFloat($(this).val());
  });
  $('#saldo_final_total_sindescuento').val(sum1);
 
}

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

/* Modificar linea de producto */
function modificar(i) {
  // verifico que no pase el maximo de stock
  var cant = parseFloat($('#cant_producto_'+i+'').val());
  var max  = parseFloat($('#cant_producto_'+i+'').attr('max'));
    if (cant > max ){
       alert('El stock que dispone de ese producto es de: '+max);
       $('#cant_producto_'+i+'').val(max);

    }
        // Actualizo factura
        var precio_unitario = parseFloat($('#precio_producto_'+i+'').val());
        var cant = parseFloat($('#cant_producto_'+i+'').val());
        var precio_parcial = (precio_unitario * cant);
        $('#precio_total_sindescuento'+i+'').val(precio_parcial);
        // checo si tiene descuento aplicado 
        var descuento =  $('#descuento_producto_'+i).val();
        if (descuento) {
          var descuento = precio_parcial * descuento / 100 ;
          var precio_parcial = precio_parcial - descuento;
        }
        $('#precio_total_'+i+'').val(precio_parcial);

  // Calculos + Precio total + Tabla conceptos
  calcular_total();



}

function modificarMax(i){

  var sigue = true;
  var valor = $('#tipo_producto_'+i).val();
  var producto = $('#id_producto_'+i).val();

  $('.prod_variante').each(function(){
    if($(this).attr('rel') == producto+valor){
      sigue = false;
    }
  });

  if(!sigue) {
    alert('Ya hay un producto cargado con la misma variante.');
    $('#tipo_producto_'+i).val('');
    $('#prod_variante_'+i).attr('rel','');
  } else {
    $('#prod_variante_'+i).attr('rel',producto+valor);
    $('#cant_producto_'+i).attr('max',$('#tipo_producto_'+i).find(':selected').data('maximo'));
    $('#cant_producto_'+i).attr("readonly", false);
  }

}

function cargar_producto_lista (data) {
  if (!data.stocktotal) {
     alert('ARTICULO SIN STOCK');
     return false;
  }
  
  // Agregar el item a la factura
    var i = parseInt($("#iterador_producto").val());
    
    var select_stock = armarSelect(data.stock,i); 
    
    var newRowContent = '<tr class="prod_variante" id="prod_variante_'+i+'" rel="">';

    newRowContent += '<td>'+data.producto.prod_codigo+' - '+data.producto.cat_nombre+' > '+data.producto.marca_nombre+' > '+data.producto.prod_nombre+' <a style="display:inline" class="tooltip"> <i class="fa fa-eye" aria-hidden="true"></i><span style="width: 120px;"><img style="width:100px;heigth:100px" src="'+data.producto.prod_img1+'"></a</td>';
    //si el producto viene con imagen, muestra el ojo para verla
    if (data.producto.prod_img == "") {

    }
    newRowContent += '<input type="hidden" value="'+data.producto.prod_id+'" id="id_producto_'+i+'" name="detalle['+i+'][id]"></td>';
    newRowContent += '<td>'+select_stock+'</td>';
    newRowContent += '<td><input type="number" value="1" min="1" class="cantidades soloNumeros full-ancho" id="cant_producto_'+i+'" name="detalle['+i+'][cant]" oninput="modificar('+i+')"></td></td>';
    newRowContent += '<td><input type="number" readonly value="'+data.producto.prod_precio * data.valordolar+'" id="precio_producto_'+i+'" name="detalle['+i+'][precio]"></td>';
    newRowContent += '<td><input type="number"  id="descuento_producto_'+i+'" name="detalle['+i+'][descuento]" onchange="porc_desc('+i+');"> </td>';
    newRowContent += '<td><input type="number"  class="precio_parc" readonly value="'+data.producto.prod_precio * data.valordolar+'" id="precio_total_'+i+'" name="detalle['+i+'][total]"> </a></td><td><a href="#" class="borrar"><i style="color:red" class="fa fa-trash" aria-hidden="true"></i></td>';
    newRowContent += '<td style="display:none"><input type="number"  class="precio_parc_sindescuento" readonly value="'+data.producto.prod_precio * data.valordolar+'" id="precio_total_sindescuento'+i+'" name="detalle['+i+'][total_sindescuento]"> </a></td>'
    newRowContent += '</tr>';
    $("#tabla_productos tbody").append(newRowContent);
  // Limpiar el campo del producto
   
  // Calcular el total
    var sum = 0;
    $('.precio_parc').each(function() {
        sum += parseInt($(this).val());
    });
    if($('#precio_descuento').val()){
      sum -= $('#precio_descuento').val();
    }

  // Actualizar monto final e interes
    $('#saldo_final_total').val(sum);
  // Actualizar tabla de conceptos
    $('#concepto_venta').html(sum.toString());
    //$('#concepto_interes').html('0');
    $('#concepto_final').html(sum.toString());

    $("#iterador_producto").val(i+1);

}



/* Borrar producto de la factura */
$(document).on('click', '.borrar', function (event) {
  event.preventDefault();
  $(this).closest('tr').remove();
  calcular_total();
});

/* Selección de producto (Ajax) */

function armarSelect(stock,i){
  var select = '<select required style="padding:0px;text-align:center" class="select-tipo-vaca" onchange="modificarMax('+i+');" id="tipo_producto_'+i+'" name="detalle['+i+'][tipo]" required>';

  var CantidadTalles = stock.Talles.length;

  // si viene con un solo talle y un solo color que el select ya venga cargado con ese
  if( CantidadTalles == 1 ) {
      for (var k in stock.Stock){
      select += '<option data-maximo="'+stock.Stock[k]+'" value="'+k+'">'+k+'('+stock.Stock[k]+')</option>';
      // para que pueda cargar la cantidad que quiera
       $('#cant_producto_'+i).attr("readonly", false);

  } // fin for k

  } else { // sino que eliga el que quiera

  select += '<option value="">Talle</option>';
  for (var k in stock.Stock){
      if (stock.Stock[k] > 0) {
      select += '<option data-maximo="'+stock.Stock[k]+'" value="'+k+'">'+k+'('+stock.Stock[k]+')</option>';
      } 

  } // fin for k


  } // fin else if


  select += '</select>';

  return select;
}

function porc_desc(i){

  valor_original = $('#precio_producto_'+i).val();
  porcentaje = $('#descuento_producto_'+i).val();
  descuento = valor_original * porcentaje / 100;
  precio_con_descuento = valor_original - descuento;
  cantidad = $('#cant_producto_'+i).val();
  precio_con_descuento = precio_con_descuento * cantidad;
  $('#precio_total_'+i).val(precio_con_descuento);
  calcular_total();
}


function agregar_producto_lista(id) {
   $("#combo_prod").val('');
  $.ajax({
      url: 'ajax_getProductoVenta.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'
  }).done(function(data){
    //console.log(data);
      cargar_producto_lista(data);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getVenta(id) {
  $.ajax({
      url: 'ajax_getVenta.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    //console.log(data);
    $('#modal-edit-venta').html('');
      $('#modal-edit-venta').html(data);
      $('#modal-edit-venta').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getVentaSaldada(id) {
  $.ajax({
      url: 'ajax_getVentaSaldada.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    //console.log(data);
    $('#modal-edit-venta').html('');
      $('#modal-edit-venta').html(data);
      $('#modal-edit-venta').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}


function editarVenta(){
  $('#form_editar_venta').find('input').attr('disabled',false);
  $(".form-select-datalist").prop("disabled", false);
  $(".borrar").css('display','inline');
  $("#guardar-cambios").css('display','inline');
  $("#editar_modal").css('display','none');
  $("#nuevo_cliente").css('display','block');
}

$('#combo_fpago').on('input',function(){
  var i = $(this).val();
  if(i == 1){ // Contado
    $('.div-pagos').css('display','none');
    $('#div-contado').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-contado').attr('disabled',false);
  }
  if(i == 2){ // MP
    $('.div-pagos').css('display','none');
    $('#div-mercado').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-mercado').attr('disabled',false);
  }
  if(i == 3){ // Tarjeta cred
    $('.div-pagos').css('display','none');
    $('#div-credito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-credito').attr('disabled',false);
  }
  if(i == 4){ // multiple
    $('.div-pagos').css('display','none');
    $('#div-multiple').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-multiple').attr('disabled',false);
  }
  
});

function validarYsubmitear() {
// valido que si es multiple pago este pagando justo lo que es, ni + ni -
var formapago = $('#combo_fpago').val();
if (formapago == 4 ){  // multiple
  var sumatotal = 0;
var valordolar = parseFloat($('#valor_dolar').val());
var pesos = parseFloat($('#input_monto_pesos_multiple').val());
var dolares = parseFloat($('#input_monto_dolares_multiple').val());
var dolares = dolares * valordolar;
var tarjeta = parseFloat($('#input_monto_tarjeta_multiple').val());
pesos = pesos || 0;
dolares = dolares || 0;
tarjeta = tarjeta || 0;
var sumatotal = pesos + dolares + tarjeta;
var monto =  parseFloat($('#saldo_final_total').val());
  if(sumatotal == monto){
    $('#detalle_compra').submit();
  }else{
     alert('El total ingresado ('+sumatotal+') no coincide con el monto('+monto+')');
  } 

} else {

   $('#detalle_compra').submit();

}



}

$("#combo_prod_dev").change(function () {
  var selectedItem = $(this).val();
  var cant=$(this).find(':selected').data("cant");
  var precio=$(this).find(':selected').data("costo");
  $("#concepto_venta_detalle_id").val($(this).find(':selected').data("det-id"));
  $("#input_cantidad_dev").attr("max",cant);
  $("#input_costo_dev").val(precio);
});

function actualizarMonto() {
    var costo = parseFloat($('#input_costo_dev').val());
    var cantidad = parseFloat($('#input_cantidad_dev').val());
    var precio_parcial = (parseFloat(costo) * parseFloat(cantidad));
    if(cantidad > $('#input_cantidad_dev').attr('max')){
      $('#input_cantidad_dev').val($('#input_cantidad_dev').attr('max'));
      precio_parcial = parseFloat($('#input_cantidad_dev').val()) * parseFloat(costo);
    }
    $('#input_monto').val(precio_parcial);
    $("#input_costo_total").val(precio_parcial);
}


function getEstadoCC(id) {
  $.ajax({
      url: 'ajax_getEstadoCuenta.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'
  }).done(function(data){
    //console.log(id);
    $('#deuda_cliente').html('');
    $('#deuda_cliente').html(data.texto);
    $('#deuda_cliente').removeClass().addClass(data.clase);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

$('.soloNumeros').keypress(function (tecla) {
  if (tecla.charCode < 48 || tecla.charCode > 57) return false;
});

$('#combo_cli_cobro').on('change',function(){
  id = $('#combo_cli_cobro').val();
  getEstadoCC(id);
})

function cobrarVenta(valor_dolar){

  // validacion para que la menos ingrese un producto a la compra
  if ($('#saldo_final_total').val() == "" || $('#saldo_final_total').val() == 0 ) {
      alert('Por favor ingrese al menos un producto a la venta');
      //validacion para que todos los productos tengan color y talle
    }else if (validartalleycolor()) {
        alert('Por favor corrobore que todos los productos tengan talle');
       }else
       {
    //oculta los otros divs( pago en dolares|pago en tarjeta)

    var monto = $('#saldo_final_total').val();
     $("#myModalCobro").modal('show');
     $('#div-credito').css('display','none');
     $('#div-mercado').css('display','none');
     $('#montotitulo').text('$'+monto);
     $('#combo_fpago').val(1);
     $('#cambio_pesos').val(0);

     $('#div-contado').show();
     $('#input_monto1').val(monto);
     $('#input_monto_contado').val(monto);
     $('#total_dolar').val(Math.round(monto / valor_dolar));
     $('#totalendolares').html(Math.round(monto / valor_dolar));
     $('#input_monto_dolar').val(Math.round(monto / valor_dolar));
     $('#input_monto_tarjeta').val(monto);
    }

}
function validartalleycolor(){
var sintallenicolor = false;
   $('.select-tipo-vaca').each(function() {
      var valor = $(this).val();
      //si no tiene valor devuelve true
      if (!valor){
        sintallenicolor = true;
      }; 
  });
   return sintallenicolor;
   
}

//pago pesos - calcula el cambio
$("#input_monto_contado").change(function () {
  var valorcontado =  parseFloat($("#input_monto_contado").val());
  var monto =  parseFloat($('#saldo_final_total').val());
  if (monto > valorcontado) {
      alert('El valor minimo de pago debe ser $'+monto);
      $("#input_monto_contado ").val(monto);
      $("#cambio_pesos").val(0);
      
      } else {
        $("#cambio_pesos").val(valorcontado - monto);
      }
});

// pago dolares  - calcula el cambio 
function calcularvueltodolares(valordolar){
var montoenpesos =  parseFloat($('#saldo_final_total').val());
var montoendolares = Math.round(montoenpesos / valordolar);
var inputpagodolar = parseFloat($("#input_monto_dolar").val());
if(montoendolares > inputpagodolar ){
  alert('El valor minimo de pago debe ser $'+montoendolares+' USD');
  $("#input_monto_dolar").val(montoendolares);
   $("#pagodolares_cambio_pesos").val(0);
   $("#pagodolares_cambio_dolares").val(0);
}
if(montoendolares <= inputpagodolar) {
  var vueltoDolar = inputpagodolar - montoendolares ;
  var redondeo = Math.floor(vueltoDolar * valordolar);
  $("#pagodolares_cambio_pesos").val(redondeo);
  $("#pagodolares_cambio_dolares").val(0);
}
}

// modifica el vuelto en pesos calcula cuantos dolares sobran
function modificovueltoenpesos(valordolar){
  var montoenpesos =  parseFloat($('#saldo_final_total').val());
  var montoendolares = Math.round(montoenpesos / valordolar);
  var inputpagodolar = parseFloat($("#input_monto_dolar").val());
  var inputvueltopesos = $("#pagodolares_cambio_pesos").val();
  var vueltoDolar = inputpagodolar - montoendolares ;
  var vueltototalpesos = vueltoDolar * valordolar;
  if(inputvueltopesos > vueltototalpesos  ){
    alert('El valor minimo de vuelto en pesos debe ser $'+vueltototalpesos);
    $("#pagodolares_cambio_pesos").val(vueltototalpesos);
    $("#pagodolares_cambio_dolares").val(0);
  }else{
    var diferencia = vueltototalpesos - inputvueltopesos ;
    var redondeo = Math.floor(diferencia / valordolar);
     $("#pagodolares_cambio_dolares").val(redondeo);
   

 }

}
// modifica el vuelto en dolares calculo cuanto en pesos sobran
function modificovueltoendolares(valordolar){
  var montoenpesos =  parseFloat($('#saldo_final_total').val());
  var montoendolares = Math.round(montoenpesos / valordolar);
  var inputpagodolar = parseFloat($("#input_monto_dolar").val());
  var inputvueltododolares = $("#pagodolares_cambio_dolares").val();
  var vueltoDolar = inputpagodolar - montoendolares ;
  if(inputvueltododolares > vueltoDolar  ){
    alert('El valor minimo de vuelto en dolares debe ser $'+vueltoDolar+'usd');
    $("#pagodolares_cambio_pesos").val(0);
    $("#pagodolares_cambio_dolares").val(vueltoDolar);
  }else{
   var diferencia  = vueltoDolar - inputvueltododolares ;
   var redondeo = Math.floor(diferencia*valordolar);
     $("#pagodolares_cambio_pesos").val(redondeo);

 }

}
// Eliminar venta

function ventafuera(id){
  $('#eliminar_venta_id').val(id);
  $('#modalEliminarVenta').modal('show');
}



// Eliminar Gasto
function gastoFuera(id,concepto,monto){
  $('#eliminar_gasto_id').val(id);
  $('#eliminar_gasto_monto').val(monto);
  $('#concepto_gasto').text(concepto);
  $('#modalEliminarGasto').modal('show');
}



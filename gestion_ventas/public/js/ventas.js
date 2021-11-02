
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
}

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

/* Modificar linea de producto */
function modificar(i) {
  // Actualizo factura
    var precio_unitario = parseFloat($('#precio_producto_'+i+'').val());
    var cant = parseFloat($('#cant_producto_'+i+'').val());
    var precio_parcial = (precio_unitario * cant);
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
  }

}

function cargar_producto_lista (data) {
  
  // Agregar el item a la factura
    var i = parseInt($("#iterador_producto").val());

    if(i == 0){
      $('#boton_descuento').show();
    }
    
    var select_stock = armarSelect(data.stock,i); 
    
    var newRowContent = '<tr class="prod_variante" id="prod_variante_'+i+'" rel="">';

    newRowContent += '<td>'+data.producto.prod_codigo+' - '+data.producto.cat_nombre+' > '+data.producto.marca_nombre+' > '+data.producto.prod_nombre+'</td>';
    newRowContent += '<input type="hidden" value="'+data.producto.prod_id+'" id="id_producto_'+i+'" name="detalle['+i+'][id]"></td>';
    newRowContent += '<td>'+select_stock+'</td>';
    newRowContent += '<td><input type="number" value="1" min="1" class="soloNumeros full-ancho" id="cant_producto_'+i+'" name="detalle['+i+'][cant]" oninput="modificar('+i+')"></td></td>';
    newRowContent += '<td><input type="number" readonly value="'+data.producto.prod_precio+'" id="precio_producto_'+i+'" name="detalle['+i+'][precio]"></td>';
    newRowContent += '<td><input type="number"  class="precio_parc" readonly value="'+data.producto.prod_precio+'" id="precio_total_'+i+'" name="detalle['+i+'][total]"> <a href="#" class="borrar"><i class="fa fa-times" aria-hidden="true"></i></a></td>';
    
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
  var select = '<select required class="select-tipo-vaca" onchange="modificarMax('+i+');" id="tipo_producto_'+i+'" name="detalle['+i+'][tipo]" required>';
  select += '<option value="">Color / Talle</option>';
  for (var k in stock.Stock){
    for (var j in stock.Stock[k]){
      select += '<option data-maximo="'+stock.Stock[k][j]+'" value="'+k+','+j+'">'+k+' - '+j+'</option>';
    }
  }
  select += '</select>';

  return select;
}

function porc_desc(){
  var total = 0;
  $('.precio_parc').each(function() {
      total += parseInt($(this).val());
  });

  var precio_desc = total * $('#porc_descuento').val() / 100;
  var precio_total_final = total - precio_desc;

  $('#precio_descuento').val(precio_desc);
  $('#precio_total_desc').val(precio_desc);
  $('#saldo_final_total').val(precio_total_final);

}

function prec_desc(){
  var total = 0;
  $('.precio_parc').each(function() {
      total += parseInt($(this).val());
  });

  var porc_desc = $('#precio_descuento').val() * 100 / total;
  var precio_total_final = total - $('#precio_descuento').val();

  $('#porc_descuento').val(porc_desc);
  $('#precio_total_desc').val($('#precio_descuento').val());
  $('#saldo_final_total').val(precio_total_final);

}

function nuevoDescuento(){
  var newRowContent = '<tr style="background-color: #c0ebf1;">';

  newRowContent += '<td> Descuento </td>';
  newRowContent += '<td></td>';
  newRowContent += '<td><input type="number" max="99" oninput="porc_desc();" class="mod_desc soloNumeros" id="porc_descuento" placeholder="%" name="porc_descuento"></td></td>';
  newRowContent += '<td><input type="number" step="0.01" oninput="prec_desc();" class="mod_desc" id="precio_descuento" placeholder="$" name="precio_descuento"></td>';
  newRowContent += '<td><input type="number" step="0.01" class="precio_parc_desc" readonly id="precio_total_desc" name="precio_total_desc"></td>';
  
  newRowContent += '</tr>';
  $("#tabla_productos tbody").append(newRowContent);

// Calcular el total
  var sum = 0;
  $('.precio_parc').each(function() {
      sum += parseInt($(this).val());
  });
  sum -= $('#precio_descuento').val();

// Actualizar monto final e interes
  $('#saldo_final_total').val(sum);
  $('#boton_descuento').hide();
  $('#contenedor-productos').hide();

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

function getVentaPendiente(id) {
  $.ajax({
      url: 'ajax_getVentaPendiente.php',
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
  if(i == 4){ // Tarjeta deb
    $('.div-pagos').css('display','none');
    $('#div-debito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-debito').attr('disabled',false);
  }
  
});

function validarYsubmitear() {
  var cli = $('#combo_cli_cobro').val();
  var forma = $('#combo_fpago').val();
  var saldo_total = $('#saldo_final_total').val();
  var sigue = 1;

  /* Valido montos */
    var monto = false;

    if(!forma) {
      alert('Debe seleccionar una forma de pago');
      return false;
    } else {
      switch(forma) {
        case '1':
          monto = $('#input_monto_contado').val(); // 1. Contado
          break;
        case '2':
          monto = $('#input_monto_dolar').val(); // 2. MP
          break;

        case '3':
          monto = $('#input_monto_tarjeta').val(); // 3. Credito 
          break;
      }

      if(!monto || monto < 0) {
        alert('Debe ingresar un monto válido');
        return false;
      } else {
        if(parseInt(monto) < parseInt(saldo_total)) {
        
        if(cli == 9999){
          alert('El monto no puede ser menor al saldo total de la venta.');
          return false;
        }else{
          if(confirm('El pago es menor al saldo total de la venta. Desea continuar?')){
            sigue = sigue * 1;
          } else {
            return false;
          }
        }
        } else { // El monto es mayor o igual al saldo
          sigue = sigue * 1;
        }
      }
    }

  // Valido cliente

    if(cli == 9999) {
      if(forma == 6 || forma == 5){
        alert('Debe ingresar un cliente existente para esta forma de pago.');
        return false;
      } else {
        if(confirm('No seleccionó cliente. Se cargará un pago anónimo sin asiento en cuenta corriente de clientes.')){
          sigue = sigue * 1;
        } else {
          return false;
        }
      }
    }

  // Valido cheque

    if(forma == 6){
      var nro = $('#input_numero_cheque').val();
      var fcobro = $('#input_cobro_cheque').val();
      var fcobromax = $('#input_cobro_cheque').attr('max');
      var bco = $('#input_banco_cheque').val();
      var titular = $('#input_titular_cheque').val();
      var femision = $('#input_emision_cheque').val();
      var femisionmax = $('#input_emision_cheque').attr('max');

      if(!fcobro || !femision || fcobro < femision){
        alert('La fecha de cobro debe ser mayor que la fecha de emisión del cheque.');
        return false;
      } else if(femision > femisionmax){
        alert('La fecha de emision no puede ser mayor a '+femisionmax);
        return false;
      } else if(fcobro > fcobromax){
        alert('La fecha de cobro no puede ser mayor a '+fcobromax);
        return false;
      } else if(!nro){
        alert('Número de cheque incorrecto.');
        return false;
      } else if(!bco){
        alert('Por favor seleccione un banco');
        return false;
      } else if(!titular){
        alert('Por favor ingrese titular.');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

    // Valido transferencias

    if(forma == 8){
      var emisor = $('#input_banco_emisor_t').val();
      var receptor = $('#input_banco_receptor_t').val();

      if(!emisor || !receptor){
        alert('Ingrese todos los datos necesarios.');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

    // Valido depositos

    if(forma == 9){
      var emisor = $('#input_banco_d').val();

      if(!emisor){
        alert('Debe seleccionar un banco.');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

    if (sigue){
      $('#form_cobro').submit();
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

function cobrarVenta(venta, cliente,monto,valor_dolar){
  $('#venta_id').val(venta);
  $('#combo_cliente_cobro').val(cliente).trigger('change').attr('disabled',true);
  if(cliente){
    $('#input_id_cliente').val(cliente); 
  }

  $('#div-contado').css('display','none');
  $('#div-debito').css('display','none');
  $('#combo_fpago').val(1);
  $('#div-contado').show();
  $('#input_monto').val(monto);
  $('#input_monto_contado').val(monto);
  $('#input_monto_dolar').val(monto / valor_dolar);
  $('#input_monto_tarjeta').val(monto);
}

// validacion para que la menos ingrese un producto a la compra
document.getElementById('detalle_compra').addEventListener('submit', function(evt){
    evt.preventDefault();
    if ($('#saldo_final_total').val() == "") {
      alert('Por favor ingrese al menos un producto a la venta');
    }else {
       $('#detalle_compra')[0].submit();
    }
    
});




/* CUSTOM JS DE INTRANET-GAM */
/* 20-05-2017 */

function agregarCategoria(){
  var nombre = prompt('Nombre de la categoría: ');
  $.ajax({
      url: 'ajax_addCategoria.php',
      type: 'POST',
      data: {nombre : nombre},
      dataType: 'json'
  }).done(function(data){
      var newOption = new Option(nombre, data, false, false);
      $('#input_categoria_add').append(newOption).trigger('change');
      $('#input_categoria_add').val(data);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}

function agregarMarca(){
  var nombre = prompt('Nombre de la Marca: ');
  $.ajax({
      url: 'ajax_addMarca.php',
      type: 'POST',
      data: {nombre : nombre},
      dataType: 'json'
  }).done(function(data){
      var newOption = new Option(nombre, data, false, false);
      $('#input_marca_add').append(newOption).trigger('change');
      $('#input_marca_add').val(data);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}

function agregarProducto(){
  var cat = $('#input_categoria_add').val();
  var marca = $('#input_marca_add').val();
  var nombre = $('#input_modelo_add').val();
  var codigo = $('#input_codigo_add').val();
  var precio = $('#input_precio_add').val();
  if (cat && marca && nombre && codigo && precio) {


  $.ajax({
      url: 'ajax_addProducto.php',
      type: 'POST',
      data: {
        input_categoria: cat,
        input_marca: marca,
        input_modelo: nombre,
        input_codigo: codigo,
        input_precio: precio,
      },
      dataType: 'json'
  }).done(function(data){
      var newOption = new Option(data.nom_prod, data.id_prod, false, false);
      $('#combo_prod').append(newOption);
      $('#agregarProducto').modal('hide');
      $('.campos_prod').val('');
      $('#input_categoria_add').val('').trigger('change');
      $('#input_marca_add').val('').trigger('change');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });

    }else {
       alert('Por favor complete todos los campos');

    }
}

/* Alta de proveedor con Ajax */
function ajax_guardarProveedor() {

  var data = JSON.stringify( $('#detalle_cliente_add').serializeArray() ); 
  console.log(data);
  
  $.ajax({
      url: 'ajax_addProveedor.php',
      type: 'POST',
      data: {data : data},
      dataType: 'json'
  }).done(function(data){
      var newOption = new Option(data.nombre, data.id, false, false);
      $('#combo_prov').append(newOption).trigger('change');
      $('#combo_prov').val(data.id);
      $('#myModalProveedorAdd').modal('hide');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function calcular_total() {
  var sum = 0;
  $('.precio_parc').each(function() {
    $val = $(this).val();
    if ($val != ''){ sum += parseFloat($val);}
  });
  $('#saldo_final_total').val(sum);
}

function modificar(i) {
  // Actualizo factura
    var peso_total = parseFloat($('#cantidad_'+i+'').val());
    var precio_kg = parseFloat($('#precio_kg_'+i+'').val());

    var precio_parcial = (peso_total * precio_kg);

    $('#precio_total_'+i+'').val(precio_parcial);

    calcular_total();
}

function cargar_producto_lista (prod_modelo, prod_id) {

  var i = parseInt($('#cant_prod').val());

  var newRowContent = '<tr>';
  newRowContent += '<td>'+prod_modelo+' <input type="hidden" id="prod_id_'+i+'" name="prod['+i+'][id]" value="'+prod_id+'"></td>';
  newRowContent += '<td><select class="select-tipo-vaca" id="listado-talles_'+i+'" name="prod['+i+'][talle]" required>';
  newRowContent += '<option value="">Seleccione</option>'; 
    $.each(talles,function(key, value) 
    { newRowContent += '<option value=' + key + '>' + value + '</option>'; });
  newRowContent += '</select></td>';
  newRowContent += '<td><input type="number" step="1" min="1" id="cantidad_'+i+'" name="prod['+i+'][cantidad]" oninput="modificar('+i+');" required></td>';
  newRowContent += '</tr>';

  $("#tabla_productos tbody").append(newRowContent);
  $("#combo_prod").val('');
  
  calcular_total();

  $('#cant_prod').val(i+1);
}

$(document).on('click', '.borrar', function (event) {
  event.preventDefault();
  $(this).closest('tr').remove();
  calcular_total();
});

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getCompra(id) {
  $.ajax({
      url: 'ajax_getCompra.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-compra').html('');
    $('#modal-edit-compra').html(data);
    $('#modal-edit-compra').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

document.getElementById('detalle_compra').addEventListener('submit', function(evt){
    evt.preventDefault();
    if ($('#saldo_final_total').val() == "") {
      alert('Por favor ingrese un producto al menos en la compra');
    } else {
      if($('#detalle_compra')[0].checkValidity()){
        if(confirm('Por favor revise los datos de la compra.  \nSi son correctos click en OK para guardar.')){
          $('#detalle_compra')[0].submit();
        }
      }
    }
    
});

function editarCompra(){
  $(".form-select-prov").prop("disabled", false);
  $(".form-select-transp").prop("disabled", false);
  $(".editable").prop("disabled", false);
  $("#guardar-cambios").css('display','inline');
  $("#nuevo_prov").css('display','inline');
  $("#editar_modal").css('display','none');
}

$('#combo_fpago').on('input',function(){
  var i = $(this).val();
  if(i == 1){ // Contado
    $('.div-pagos').css('display','none');
    $('#div-contado').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-contado').attr('disabled',false);
  }
  if(i == 2){ // Mp
    $('.div-pagos').css('display','none');
    $('#div-mercado').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-mercado').attr('disabled',false);
  }
  if(i == 3){ // Cred
    $('.div-pagos').css('display','none');
    $('#div-credito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-credito').attr('disabled',false);
  }

  if(i == 4){ // Deb
    $('.div-pagos').css('display','none');
    $('#div-debito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-debito').attr('disabled',false);
  }

});

function validarYsubmitear() {

  var prov = $('#combo_prov_pago').val();
  var forma = $('#combo_fpago').val();
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
          monto = $('#input_monto_mercado').val(); // 2. mp
          break;
        case '3':
          monto = $('#input_monto_credito').val(); // 6. cred
          break;
        case '4':
          monto = $('#input_monto_debito').val(); // 8. deb
          break;
      }

      if(!monto) {
        alert('Debe ingresar un monto válido');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

  // Valido cliente
    if(!prov) {
      alert('Debe ingresar un proveedor existente.');
      return false;
    }

  // Valido cheque

    if(forma == 2){
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

    if (sigue){
      $('#form_pago').submit();
    }

}
function actualizarMonto() {
  var costou =  $('#input_costo_unitario').val();
  var cantidad =  $('#input_cantidad').val();
  total = costou*cantidad;

  $('#input_monto').val(total);
}
function inputsConcepto() {


  switch ($('#combo_tipo').val()) {
     case '1':
          $('#input_cantidad').val();
          $('#input_costo_unitario').val();
          $('#input_monto').val();
          $('#div-precio-unitario').show();
          $('#div-cantidad').show();
          $('#div-monto').show();
          $('#input_monto').attr("readonly","true");
          $('#div-devolucion').hide();

      break;
      case '2':
      if($('#concepto_transp_id').val() == 0){
        alert("La compra no tiene un transportista asignado");
        $('#combo_tipo').val(0);
        $('#div-precio-unitario').hide();
        $('#div-cantidad').hide();
        $('#div-monto').hide();
        return false;
      }
          $('#div-monto').show();
          $('#div-precio-unitario').hide();
          $('#div-cantidad').hide();
          $('#input_monto').removeAttr("readonly");
          $('#div-devolucion').hide();

       break;
     case '3':
      if($('#concepto_transp_id').val() == 0){
        alert("La compra no tiene un transportista asignado");
        $('#combo_tipo').val(0);
        $('#div-precio-unitario').hide();
        $('#div-cantidad').hide();
        $('#div-monto').hide();
        return false;
      }
          $('#input_cantidad').val();
          $('#input_costo_unitario').val();
          $('#input_monto').val();
          $('#div-precio-unitario').show();
          $('#div-cantidad').show();
          $('#div-monto').show();
          $('#input_monto').attr("readonly","true");
          $('#div-devolucion').hide();

      break;
      case '4':
          $('#div-precio-unitario').hide();
          $('#div-cantidad').hide();
          $('#div-monto').hide();
      break;
      case '5':
          $('#div-precio-unitario').hide();
          $('#div-cantidad').hide();
          $('#div-monto').show();
          $('#input_monto').removeAttr("readonly");
          $('#div-devolucion').hide();

          
      break;
      case '6':
          $('#div-precio-unitario').hide();
          $('#div-cantidad').hide();
          $('#div-monto').show();
          $('#input_monto').removeAttr("readonly");
          $('#div-devolucion').hide();


      break;
      case '7':
          $('#div-precio-unitario').hide();
          $('#div-cantidad').hide();
          $('#div-monto').show();
          $('#input_monto').removeAttr("readonly");
          $('#div-devolucion').hide();


      break;
      case '8':
          $('#div-precio-unitario').hide();
          $('#div-cantidad').hide();
          $('#div-monto').hide();
          $('#input_monto').removeAttr("readonly");
          devolucionMercaderia($('#concepto_compra_id').val());

      break;

     default:
  }


}


function getEstadoCC(id) {
  $.ajax({
      url: 'ajax_getEstadoCuenta.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'
  }).done(function(data){
    //console.log(id);
    $('#deuda_prov').html('');
    $('#deuda_prov').html(data.texto);
    $('#deuda_prov').removeClass().addClass(data.clase);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}


$('#myModalPago').on('shown.bs.modal', function () {
  id = $('#combo_prov_pago').val();
  getEstadoCC(id);
})  

function validarConcepto() {
  var tipo = $('#combo_tipo').val();
  var transp = $('#concepto_transp_id').val();
  if (transp == "0" && (tipo == 2 || tipo == 3)) {
    alert('No existe transportista asociado a la compra.');
    return false;
  } else {
    $('#form_concepto').submit();
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

$('.soloNumeros').keypress(function (tecla) {
  if (tecla.charCode < 48 || tecla.charCode > 57) return false;
});

function devolucionMercaderia(id) {
  $.ajax({
      url: 'ajax_getDevolucionMercaderia.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    console.log(data);
    $.each(JSON.parse(data), function(i,item) {
      if(item.cant){
        var option = "<option value="+item.id+" data-cant="+item.cant+" data-det-id="+item.det_id+" data-costo="+item.valor+">"+item.nombre+"</option>";
        $("#combo_prod_dev").append(option);
      }
    });

    $('#div-devolucion').show();
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}


$("#combo_prod_dev").change(function () {
var selectedItem = $(this).val();
var cant=$(this).find(':selected').data("cant");
var precio=$(this).find(':selected').data("costo");
$("#concepto_compra_detalle_id").val($(this).find(':selected').data("det-id"));
$("#input_cantidad_dev").attr("max",cant);
$("#input_costo_dev").val(precio);
});

function actualizarMontoDevolucion() {
  // Actualizo factura
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

function focusSelect(id){
  $('#'+id).select2('open');
}
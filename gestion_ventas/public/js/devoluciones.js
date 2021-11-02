
/* -- FUNCIONES NUEVA VENTA -- */
/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});


/* Al clickear en devolucion, abre el modal nuevo con:
  - Detalle de la venta
  - Diferencias de plata
  - Productos disponibles */

function devolucionMercaderia(id_venta) {
  $.ajax({
      url: 'ajax_getDatosDevolucionMercaderia.php',
      type: 'POST',
      data: {id : id_venta},
      dataType: 'html'
  }).done(function(data){
    $('#devolucion-venta').html('');
    $('#devolucion-venta').html(data);
    $('#devolucion-venta').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function agregar_producto_devolver(elem){

  if($('#cont_'+elem.val()).length || elem.val() == ''){
    alert('Seleccione un producto válido.');
  } else {

  var prod = '<span class="form-block cont-devueltos z-depth-1" id="cont_'+elem.val()+'">' +
              '<span class="form-block titulo-devueltos"> ' +
                elem[0].text +
                '<a href="#" class="borrar-devueltos" onclick="borrar_prod('+elem.val()+')"><i class="fa fa-times" aria-hidden="true"></i></a> ' +
              '</span> ' +
              '<span class="form-block">Talle/Color: <input class="input-devueltos-tallecolor" readonly name="devuelto['+elem.val()+'][tipo]" value="'  + elem.attr('data-color') + ' - '+ elem.attr('data-talle') + '"></span>' +
              '<span class="form-block"> ' +
                'Cantidad:  ' +
                '<input type="number" oninput="calcular_totales();" min="1" name="devuelto['+elem.val()+'][cant]" max="'+elem.attr('data-cant')+'" value="1" class="input-devueltos c_dev"> ' +
                'Precio:   ' +
                '<input type="number" oninput="calcular_totales();" readonly name="devuelto['+elem.val()+'][precio]" value="'+elem.attr('data-val')+'" class="input-devueltos p_dev"> ' +
              '</span> ' +
            '</span>';

  $('#con-productos-devueltos').append(prod);
  calcular_totales();

 
 
  
  }

}

function calcular_totales(){
  /* Productos a devolver */
    var total_dev = 0;

    $('.c_dev').each(function(){
      var cant = parseInt($(this).val());
      var prec = parseFloat($(this).parent().find('.p_dev').val());

      total_dev += cant * prec; 
    });

    $('#input-devueltos-total').val(total_dev);

  /* Productos a cambiar */

    var total_camb = 0;
     
    $('.c_cam').each(function(){
      var cant = parseInt($(this).val());
      var prec = parseFloat($(this).parent().find('.p_cam').val());

      total_camb += cant * prec; 
    });

    $('#input-cambiados-total').val(total_camb);

  /* Diferencia */

    var diferencia = $('#input-devueltos-total').val() - $('#input-cambiados-total').val();
    $('#input-diferencia-total').val(diferencia);

  
    if(diferencia == 0){
      $('#div-pago').css('display','none');
      $('#div-nota').css('display','none');
    }

    if(diferencia > 0){
      $('#input_monto_nc').val(diferencia);
      $('.dis-nc').attr('disabled',false);
      $('.dis-pago').attr('disabled',true);
      $('#div-pago').css('display','none');
      $('#div-nota').css('display','block');
    }

    if(diferencia < 0){
      $('#input_monto_cobro').val(0-diferencia);
      $('.dis-pago').attr('disabled',false);
      $('.dis-nc').attr('disabled',true);
      $('#div-pago').css('display','block');
      $('#div-nota').css('display','none');
    }
}

function borrar_prod(id){
  $('#cont_'+id).remove();
  calcular_totales();
}

function borrar_dev(id){
  $('#dev_'+id).remove();
  calcular_totales();
}

/* Al seleccionar un talle/color, modifica el maximo de esa variacion en stock */
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
    $('#dev_'+i).attr('rel','');
  } else {
    $('#dev_'+i).attr('rel',producto+valor);
    $('#cant_producto_'+i).attr('max',$('#tipo_producto_'+i).find(':selected').data('maximo'));
    $('#cant_producto_'+i).attr('disabled',false);
    $('#cant_producto_'+i).val(1);
    calcular_totales();
  }

}

/* Al seleccionar un producto a cambiar, se arma el cuadrito con la variaciones y el precio */
function agregar_producto_cambiar(elem){
    if(elem.val() == ''){
    alert('Seleccione un producto válido.');
  } else {

  $.ajax({
      url: 'ajax_getProductoVenta.php',
      type: 'POST',
      data: {id : elem.val()},
      dataType: 'json'
  }).done(function(data){
    // console.log(data);
    var stock = data.stock;
    var select = '<select required class="select-talle-devolver" onchange="modificarMax('+elem.val()+');" id="tipo_producto_'+elem.val()+'" name="detalle['+elem.val()+'][tipo]" required>';
    select += '<option value="">Seleccione </option>';

    for (var k in stock.Stock){
      for (var j in stock.Stock[k]){
        select += '<option data-maximo="'+stock.Stock[k][j]+'" value="'+k+','+j+'">'+k+' - '+j+'</option>';
      }
    }

    select += '</select>';

    var prod = '<span class="form-block cont-devueltos prod_variante z-depth-1" id="dev_'+elem.val()+'" rel="">' +
                '<span class="form-block titulo-devueltos"> ' +
                  elem[0].text +
                  '<a href="#" class="borrar-devueltos" onclick="borrar_dev('+elem.val()+')"><i class="fa fa-times" aria-hidden="true"></i></a> ' +
                '</span> ' +
                '<span class="form-block">Talle/Color: '  + select + '</span>' +
                '<span class="form-block"> ' +
                  'Cantidad:  ' +
                  '<input type="number" disabled oninput="calcular_totales();" min="1" id="cant_producto_'+elem.val()+'" name="detalle['+elem.val()+'][cant]" max="" value="" class="input-devueltos c_cam"> ' +
                  'Precio:   ' +
                  '<input type="number" oninput="calcular_totales();" readonly name="detalle['+elem.val()+'][precio]" value="'+elem.attr('data-val')+'" class="input-devueltos p_cam"> ' +
                '</span> ' +
              '</span>';

    $('#con-productos-cambiar').append(prod);
    calcular_totales();

  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });

  
  
  }

}

//trae la vista de la nota de credito generada por una devolucion
function getNota(id) {
  $.ajax({
      url: 'ajax_getNota.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    console.log(data);
      $('#modal-edit-nota').html('');
      $('#modal-edit-nota').html(data);
      $('#modal-edit-nota').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}


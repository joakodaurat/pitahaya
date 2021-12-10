
/* -- Solicitudes -- */



$( window ).load(function() {
  let searchParams = new URLSearchParams(window.location.search);
  let idproducto = searchParams.get('idproducto');
   if (searchParams.has('idproducto')) { getProducto(idproducto);}
});




/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getProducto(id,premium){
	$.ajax({
      url: 'ajax_getProducto.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-producto').html('');
      $('#modal-edit-producto').html(data);
      $('#modal-edit-producto').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

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

function getMarca(id,premium) {
  $.ajax({
      url: 'ajax_getMarca.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-marca').html('');
      $('#modal-edit-marca').html(data);
      $('#modal-edit-marca').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getCategoria(id,premium) {
  $.ajax({
      url: 'ajax_getCategoria.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-categoria').html('');
      $('#modal-edit-categoria').html(data);
      $('#modal-edit-categoria').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getTalle(id,premium) {
  $.ajax({
      url: 'ajax_getTalle.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-talle').html('');
      $('#modal-edit-talle').html(data);
      $('#modal-edit-talle').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getColor(id,premium) {
  $.ajax({
      url: 'ajax_getColor.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-color').html('');
      $('#modal-edit-color').html(data);
      $('#modal-edit-color').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

$('#input_categoria').keypress(function() {
  if(event.keyCode == 13){
    validarCategoriaAdd();
    return false;
  }
});

  function editarPrecio(){
  $('#editar_precio').show();
  $('#mostrar_precio').hide();
  $('#input_precio').attr('disabled',false);
  $('#input_precio').focus();
  }

          function validarMarca(){
          var nombre = $('#input_marca_edit').val();
          var id = $('#edit_marca_id').val();

          $.ajax({
              url: 'ajax_validarMarca.php',
              type: 'POST',
              data: {nombre : nombre,
                    id : id },
              dataType: 'json'
          }).done(function(data){
            console.log(data);
            if(data && data != id){ //encontro un marca con el mismo nombre, y no es el mismo id.
               alert('Ya existe un marca con el mismo nombre. Debe ingresar un nombre distinto');
               return false;
            }else{
              $('#detalle_marca_edit').submit();
            }
          }).fail(function(xhr, textStatus, errorThrown) {
              console.log(xhr.responseText);
          }).always(function(){
              // console.log('The ajax call ended.');
          });

        }

        $('#input_marca_edit').keypress(function() {
          if(event.keyCode == 13){
            validarMarca();
            return false;
          }
        });

//MODAL MODIFICAR PRECIOS

function precio_categoria(){
  $('#editar_precio_categoria').show();
  $('#editar_precio_marca').hide();
  $('#editar_precio_todo').hide();
  //Reinicio los valores de los inputs
  $('#input_porcentaje_categoria').val('');
  $('#input_porcentaje_marca').val('');
  $('#input_porcentaje_todo').val('');
  //Campos obligatorios 
  $('#input_porcentaje_categoria').attr("required", "true");
  $('#select_categoria').attr("required", "true");
  //Campos NO obligatorios 
  $('#input_porcentaje_marca').removeAttr('required');
  $('#input_porcentaje_todo').removeAttr('required');

  $('#select_marca').removeAttr('required');
  }
function precio_marca(){
  $('#editar_precio_categoria').hide();
  $('#editar_precio_marca').show();
  $('#editar_precio_todo').hide();
  //Reinicio los valores de los inputs
  $('#input_porcentaje_categoria').val('');
  $('#input_porcentaje_marca').val('');
  $('#input_porcentaje_todo').val('');
  //Campos obligatorios 
  $('#input_porcentaje_marca').attr("required", "true");
  $('#select_marca').attr("required", "true");
  //Campos NO obligatorios 
  $('#input_porcentaje_categoria').removeAttr('required');
  $('#input_porcentaje_todo').removeAttr('required');

  $('#select_categoria').removeAttr('required');
  }
function precio_todo(){
  $('#editar_precio_categoria').hide();
  $('#editar_precio_marca').hide();
  $('#editar_precio_todo').show();
  //Reinicio los valores de los inputs
  $('#input_porcentaje_categoria').val('');
  $('#input_porcentaje_marca').val('');
  $('#input_porcentaje_todo').val('');
  //Campos obligatorios 
  $('#input_porcentaje_todo').attr("required", "true");
  //Campos NO obligatorios 
  $('#input_porcentaje_categoria').removeAttr('required');
  $('#input_porcentaje_marca').removeAttr('required');

  $('#select_categoria').removeAttr('required');
  $('#select_marca').removeAttr('required');
  }
// Para tener una vista previa de las imagenes antes de subirlas
function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
              switch(input.id) {
                  case "input1":
                  $('#image1').show();
                  $('#image1').attr('src', e.target.result);
                  $('#botonimagen2').show();
                    break;
                  case "input2":
                  $('#image2').show();
                  $('#image2').attr('src', e.target.result);
                  $('#botonimagen3').show();
                    break;
                  case "input3":
                  $('#image3').show();
                  $('#image3').attr('src', e.target.result);
                    break;
                  case "input4":
                  $('#image4').show();
                  $('#image4').attr('src', e.target.result);
                    break;
                }
            
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $(".inputImage").change(function(){
        readURL(this);
    });



// PARA LOS MODALES DE MODIFICAR STOCK

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


function eliminarimagen(idproducto,url) {
  $.ajax({
      url: 'ajax_deleteImagen.php',
      type: 'POST',
      data: {idproducto : idproducto, url:url},
  }).done(function(data){
     window.location.href += "?idproducto="+idproducto;
    windows.location.reload();
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}
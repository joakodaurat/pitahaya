
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});


function actualizarHistorial() {
  $('#contenedor-datos-caja').html('');
  $('#contenedor-esperando').css('display','block');

  var fecha = $('#fecha_historial').val();

  $.ajax({
      url: 'ajax_actualizarHistorial.php',
      type: 'POST',
      data: {fecha : fecha},
      dataType: 'html'
  }).done(function(data){
      //console.log(data);
      $('#contenedor-datos-caja').css('display','none');
      $('#contenedor-datos-caja').html(data);
      $('#contenedor-esperando').delay( 1500 ).fadeOut( 400 );
      $('#contenedor-datos-caja').delay( 2000 ).fadeIn( 900 );
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getDatosCajaParticular(id,e) {
  if (e) {
  $(".filaCajas").removeClass("active");
  $(e).addClass("active");
  }

  $('#spinercargadorl').delay( 2000 ).fadeIn( 900 );
  $('#datoscajaindividual').html('');
  $('#avisoqueseleccionecaja').hide();
  $('#spinercargador').show();

  $.ajax({
      url: 'ajax_getDatosCaja.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      //console.log(data);
      $('#datoscajaindividual').css('display','none');
      $('#datoscajaindividual').html(data);
      $('#spinercargador').delay( 1500 ).fadeOut( 400 );
      $('#datoscajaindividual').delay( 2000 ).fadeIn( 900 );
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}
function getCobro(id) {
  $.ajax({
      url: 'ajax_getCobro.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    console.log(data);
      $('#modal-edit-cobro').html('');
      $('#modal-edit-cobro').html(data);
      $('#modal-edit-cobro').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

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




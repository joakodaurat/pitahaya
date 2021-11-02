
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function abrirCaja() {
    var monto = $('#monto_inicial').val();
    if(!monto) {
      alert('Debe ingresar un monto.');
    } else {
      $('#abrir_caja').submit();
    }
}

function actualizarCaja() {
  $('#contenedor-datos-caja').html('');
  $('#contenedor-esperando').css('display','block');

  var fecha = $('#fecha_historial').val();

  $.ajax({
      url: 'ajax_actualizarCaja.php',
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

function getDetalleCC(tipo,id){
  if(tipo == 1){
    getVenta(id);
  } 
  if(tipo == 3){
    getCobro(id);
  }
  if(tipo == 5 || tipo == 6){
    getNota(id);
  }
}

function modalCerrarCaja(id) {
  $('#div-cerrar-caja').html('');

 $('#modalCerrarCaja_').modal('show');
}

function CerrarCaja(id) {
  $('#modalCerrarCaja_').modal('hide');
  $('#contenedor-datos-caja').hide(); 
  $('#contenedor-esperando-cierre').show();
  $.ajax({
      url: 'ajax_CerrarCaja.php',
      type: 'POST',
      data: {caja_id : id}
        }).done(function(data){
      console.log(data);
      
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

$('.soloNumeros').keypress(function (tecla) {
  if (tecla.charCode < 48 || tecla.charCode > 57) return false;
});

function vertablaventas(){ 
  
  $('#tablaventasxproducto').hide();
  $('#tablaventas').show();
};
function vertablaventasxarticulo(){
    $('#tablaventas').hide();
    $('#tablaventasxproducto').show();
};
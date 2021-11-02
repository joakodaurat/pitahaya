
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

/* Solo numeros en los inputs numericos */
 $('.soloNumeros').keypress(function (tecla) {
    if (tecla.charCode < 48 || tecla.charCode > 57) return false; });

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

      if(!monto || monto < 1) {
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


    if (sigue){
      $('#form_pago').submit();
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

$('#combo_prov_pago').on('change',function(){
  id = $('#combo_prov_pago').val();
  getEstadoCC(id);
});

function getPago(id) {
  $.ajax({
      url: 'ajax_getPago.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
     // console.log(data);
      $('#modal-ver-pago').html('');
      $('#modal-ver-pago').html(data);
      $('#modal-ver-pago').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
     // console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}
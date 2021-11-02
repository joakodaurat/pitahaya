
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});


document.getElementById('detalle_venta').addEventListener('submit', function(evt){
    evt.preventDefault();
    if($('#detalle_venta')[0].checkValidity()){
      if(confirm('Por favor revise los datos del pago.  \nSi son correctos click en OK para guardar.')){
        $('#detalle_venta')[0].submit();
      }
    }
});

$('#combo_fpago').on('input',function(){
  var i = $(this).val();
  if(i == 1){
    $('#div-cheque').css('display','none');
    $('#div-contado').css('display','block');
    $('.form-cheque').attr('disabled',true);
    $('.form-contado').attr('disabled',false);
  } else{
    if(i == 2){
      $('#div-cheque').css('display','block');
      $('#div-contado').css('display','none');
      $('.form-cheque').attr('disabled',false);
      $('.form-contado').attr('disabled',true);
    } else {
      $('#div-cheque').css('display','none');
      $('#div-contado').css('display','none');
    }
  }
});
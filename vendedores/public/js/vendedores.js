//NUEVO VENDEDOR
// Valido que las dos contraseñas sean iguales antes de enviar
document.getElementById('detalle_vendedor_add').addEventListener('submit', function(evt){
    evt.preventDefault();

    // Obtenemos los valores de los campos de contraseñas 
    pass1 = document.getElementById('input_contraseña_vendedor');
    pass2 = document.getElementById('input_contraseña_vendedor_repetida');
 
    // Verificamos si las constraseñas no coinciden 
    if (pass1.value != pass2.value) {
 
        // Si las constraseñas no coinciden mostramos un mensaje 
       alert ("Las contraseñas no coinciden");  
       $('#boton_submit').css("box-shadow","none");
        return false;
    } else {
        // Si las contraseñas coinciden mandamos el submit
        if ( $('#detalle_vendedor_add')[0].checkValidity() ) {

  		  $('#detalle_vendedor_add').submit();	  

			} else {return false;}       
        }

});

//Cuando se cierran los modales no queden Marcados lo botones
$('#nuevoVendedor').on('hidden.bs.modal', function () {
  quitarsombrasbotones()
})

function quitarsombrasbotones() {
  $('#boton_nuevo_administrador').css("box-shadow","none");
  $('#boton_nuevo_vendedor').css("box-shadow","none");
    
}
//FIN NUEVO VENDEDOR
//NUEVO ADMINISTRADOR
// Valido que las dos contraseñas sean iguales antes de enviar
document.getElementById('detalle_administrador_add').addEventListener('submit', function(evt){
    evt.preventDefault();

    // Obtenemos los valores de los campos de contraseñas 
    pass1 = document.getElementById('input_contraseña_administrador');
    pass2 = document.getElementById('input_contraseña_administrador_repetida');
 
    // Verificamos si las constraseñas no coinciden 
    if (pass1.value != pass2.value) {
 
        // Si las constraseñas no coinciden mostramos un mensaje 
       alert ("Las contraseñas no coinciden");  
       $('#boton_submit_administrador').css("box-shadow","none");
        return false;
    } else {
        // Si las contraseñas coinciden mandamos el submit
        if ( $('#detalle_administrador_add')[0].checkValidity() ) {

        $('#detalle_administrador_add').submit();    

      } else {return false;}       
        }

});
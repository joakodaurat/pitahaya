<?php


/**
 * Table Definition for mail
 */


class DataObjects_Mail extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mail';                // table name
    public $m_id;                            // int(11)  not_null primary_key auto_increment group_by
    public $m_receptor;                      // varchar(256)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Mail',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    function enviar_caja_mail($objeto){
        // traigo la caja en particular
    	$caja = DB_DataObject::factory('caja');
    	$caja -> caja_id = $objeto['caja_id'];
    	$caja -> find(true);
    	$u_abrio = DB_DataObject::factory('usuario');
    	$u_abrio -> usua_id = $caja -> caja_usua_inicio;
    	$u_abrio -> find(true);
    	$usuario = $u_abrio -> usua_nombre;
    	$horainicio = date('H:i:s', strtotime($caja -> caja_fh_inicio));
    	$horacierre = date('H:i:s');
    	$efectivoTotal = $caja -> caja_monto_inicio + $caja -> caja_pagos_efectivo;
    	$efectivoTotalDolar = $caja -> caja_monto_dolar_inicio + $caja -> caja_pagos_dolar;
        // Traigo las ventas realizadas en esa caja
    	$venta = DB_DataObject::factory('venta');
    	$do_ventas = $venta -> getVentas($caja -> caja_fh_inicio ,date('Y-m-d H:i:s'));
 		// Traigo los gastos realizados
    	$gastos = DB_DataObject::factory('gasto');
		$gastos -> gasto_caja_id = $objeto['caja_id'];
    	$gastos -> find();

    	$mail = new PHPMailer;
    	$mail->isSMTP();
    	$mail->setFrom('no-reply@cioc.com.ar', 'STOCKY');
    	$mail->addAddress('joako.daurat@gmail.com', '');
    	$mail->Username = 'AKIA43OFAS6E7BDNVBRP';
    	$mail->Password = 'BMaaER/htBnTSJGBj54h8uOOVajFTeSTnOP6s04kFdB1';
    	$mail->Host = 'email-smtp.us-east-1.amazonaws.com';
    	$mail->Subject = 'Nuevo cierre de caja';
    	$mail->Body =
    	"
    	<html>
    	<head>
    	<style>
    	.titulotabla {text-align:left;}
    	</style>
    	</head>
    	<h2>Cierre de caja</h2>
    	<p><strong>Hora apertura: </strong>".$horainicio." <p>
    	<p><strong>Hora cierre: </strong>".$horacierre." <p>
    	<p><strong>Abierta por: </strong>".$usuario." <p>
    	<p><strong>Valor del dolar: </strong>".$caja -> caja_valor_dolar." <p>
    	<p><strong>Monto inicial: </strong>".$caja -> caja_monto_inicio."$ + ".$caja -> caja_monto_dolar_inicio."usd <p>
    	
    	<p><strong>Monto final: </strong>".$efectivoTotal."$ + ".$efectivoTotalDolar."usd + ".$caja -> caja_pagos_tarjeta."$ en tarjeta <p>
    	<hr>
    	<h4>Ventas</h4>
    	";
    	if ($do_ventas -> N > 0){

    		while ($do_ventas->fetch()){
    			$producto = $do_ventas -> getDetalleArticuloPrecio();
    			$monto = $do_ventas -> venta_monto_total;
    			$hora = date('H:m:s', strtotime($do_ventas -> venta_fh));
    			$formapago = $do_ventas -> getDetalleFormaPago();
    			$mail->Body .=	
    			"<p><strong>Producto: </strong>".$producto."
    			<strong>Monto: </strong>$".$monto."
    			<strong>Hora:</strong> ".$hora."
    			<strong>Forma de pago:</strong> ".$formapago."
    			<p>";
    		}} else 
    		{
    			$mail->Body .= "<h3>No hubo ventas</h3>";	
    		}
    		$mail->Body .="<hr><h4>Gastos<h4>";
    		if ($gastos -> N > 0){

    		while ($gastos -> fetch()){
    			$concepto = $gastos -> gasto_concepto;
    			$conceptomonto = $gastos -> gasto_monto_total;
    			$mail->Body .=	
    			"<p><strong>Gasto: </strong>".$concepto."
    			<strong> - /strong>$".$conceptomonto."
    			<p>";
    		}} else 
    		{
    			$mail->Body .= "<h3>No hubo gastos</h3>";	
    		}
    		
    		$mail->Body .="</html>";
    		$mail->SMTPAuth = true;
    		$mail->SMTPSecure = 'tls';
    		$mail->Port = 587;
    		$mail->isHTML(true);
    		$mail->AltBody = "Cierre de caja";

    		if(!$mail->send()) {
    			return false;
           // echo "Email not sent. " , $mail->ErrorInfo , PHP_EOL;
    		} else {
    			return true;
           // echo "Email sent!" , PHP_EOL;
    		}

    	}}

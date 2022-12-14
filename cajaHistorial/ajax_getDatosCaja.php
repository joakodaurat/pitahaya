<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// $prem = $_POST['premium'];

	// Traigo la caja en particular
	$caja = DB_DataObject::factory('caja');
	$caja -> caja_id = $_POST['id'];
	$caja -> find(true);

	// Tipos de pago para el resumen de ingresos
	$forma_pagoc = DB_DataObject::factory('forma_pago');
	$forma_pagoc -> find();

	$gasto = DB_DataObject::factory('gasto');
	$cobro = DB_DataObject::factory('cobro_cliente');
	$venta = DB_DataObject::factory('venta');

	//Gastos

	$gastos = DB_DataObject::factory('gasto');
	$gastos -> gasto_caja_id = $caja -> caja_id;
    $gastos -> find();
	

	// Resumen caja
	$cajaIngresosDiarios = $cobro -> getIngresosCaja($caja -> caja_fh_inicio, $caja -> caja_fh_cierre); // Ingresos
	$cajaGastosDiarios = $gasto -> getGastosCaja($caja -> caja_fh_inicio, $caja -> caja_fh_cierre); // Salidas
	$estadistica_ventas = $venta -> getBultosDiariosCaja($caja -> caja_fh_inicio, $caja -> caja_fh_cierre);

	$cajaTotal = $cajaIngresosDiarios['Total'] - $cajaGastosDiarios['Total'];
	
	$efectivoTotal = $caja -> caja_monto_inicio + $caja -> caja_pagos_efectivo;

	$efectivoTotalDolar = $caja -> caja_monto_dolar_inicio + $caja -> caja_pagos_dolar;

	if($cajaTotal < 0) {
		$clase_balance = 'rojo';
	} else {
		$clase_balance = 'verde';
	}

	$tipo_gastos = DB_DataObject::factory('tipo_gasto');
	$tipo_gastos -> find();

	$tipo_gastos = DB_DataObject::factory('tipo_gasto');
	$tipo_gastos -> find();
	
	$forma_pago = DB_DataObject::factory('forma_pago');
	$forma_pago -> find();

	$u = DB_DataObject::factory('usuario');

	$u_abrio = DB_DataObject::factory('usuario');
	$u_abrio -> usua_id = $caja -> caja_usua_inicio;
	$u_abrio -> find(true);

	// Ventas realizadas
	$venta = DB_DataObject::factory('venta');
	if($caja -> caja_fh_cierre){
		$do_ventas = $venta -> getVentas($caja -> caja_fh_inicio ,$caja -> caja_fh_cierre);
	} else {
		$do_ventas = $venta -> getVentas($caja -> caja_fh_inicio ,date('Y-m-d H:i:s'));

	}
 
	require_once('public/GetCaja.html');
	exit;
?>
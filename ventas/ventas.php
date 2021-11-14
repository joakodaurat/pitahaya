<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("first day of this month");

	$ventas = DB_DataObject::factory('venta');


	if(!$_GET['fecha_desde']){
		$do_ventas = $ventas -> getVentas($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');


	} else {
		$do_ventas = $ventas -> getVentas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}
    $ventas_detalle = DB_DataObject::factory('venta_detalle');
    $do_vendedor = DB_DataObject::factory('usuario');

    $ventas_detalle -> joinAdd($do_ventas);
    $ventas_detalle -> joinAdd($do_vendedor);
    $ventas_detalle -> find();
 	$producto = DB_DataObject::factory('producto');
 	$marca = DB_DataObject::factory('marca');
    $categoria = DB_DataObject::factory('categoria');


    $producto ->joinAdd($marca, "LEFT");	
    $producto ->joinAdd($categoria, "LEFT");
    $producto -> find();	
    $ventas_detalle ->joinAdd($producto);	
    $ventas_detalle -> find();

    $ventas_detalle -> find();
   // print_r($ventas_detalle);exit; 



	require_once('public/ventas.html');
	exit;
?>
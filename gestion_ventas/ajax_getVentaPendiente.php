<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_ventas = DB_DataObject::factory('venta');
	$do_ventas -> whereAdd('venta_id = '.$_POST['id']);
	$do_cliente = DB_DataObject::factory('cliente');

	$do_ventas -> joinAdd($do_cliente,"LEFT");

	$do_ventas -> find(true);

	$clientes = array();
	$clientes['nombre'] = $do_ventas -> cliente_nombre;

	// $do_ventas -> venta_fh = date('d/m/Y', strtotime($do_ventas -> venta_fh));

	$respuesta = array();

	$respuesta['venta'] = $do_ventas;

	// Traigo detalle de la venta
	$do_venta_detalle = DB_DataObject::factory('venta_detalle');
	$do_venta_detalle -> whereAdd('detalle_venta_id = '.$do_ventas -> venta_id);
	
	$do_producto = DB_DataObject::factory('producto');
	$do_marca = DB_DataObject::factory('marca');
	$do_categoria = DB_DataObject::factory('categoria');
	$do_color = DB_DataObject::factory('color');
	$do_talle = DB_DataObject::factory('talle');
	
	$do_producto -> joinAdd($do_marca,"LEFT");
	$do_producto -> joinAdd($do_categoria,"LEFT");
	$do_venta_detalle -> joinAdd($do_producto,"LEFT");
	$do_venta_detalle -> joinAdd($do_color,"LEFT");
	$do_venta_detalle -> joinAdd($do_talle,"LEFT");

	$do_venta_detalle -> find(); 

	while ($do_venta_detalle -> fetch()) { 
		$detalle[$do_venta_detalle -> detalle_id]['prod_id'] = $do_venta_detalle -> detalle_prod_id;
		$detalle[$do_venta_detalle -> detalle_id]['prod_codigo'] = $do_venta_detalle -> prod_codigo;
		$detalle[$do_venta_detalle -> detalle_id]['marca_nombre'] = $do_venta_detalle -> marca_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['cat_id'] = $do_venta_detalle -> cat_id;
		$detalle[$do_venta_detalle -> detalle_id]['cat_nombre'] = $do_venta_detalle -> cat_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_nombre'] = $do_venta_detalle -> prod_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_talle'] = $do_venta_detalle -> talle_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_color'] = $do_venta_detalle -> color_nombre;
		$detalle[$do_venta_detalle -> detalle_id]['prod_cant'] = $do_venta_detalle -> detalle_prod_cant;
		$detalle[$do_venta_detalle -> detalle_id]['prod_val'] = $do_venta_detalle -> detalle_prod_precio_u;
		$detalle[$do_venta_detalle -> detalle_id]['prod_tot'] = $do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant;
	}

	$caja = DB_DataObject::factory('caja');
	
	$cajaAbierta = $caja -> cajaAbiertaHoy();
$cajaActual =  $caja -> getUltimaCaja();
	require_once('public/modales/edit_venta_pendiente.html');
	exit;
?>
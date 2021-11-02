<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// Traigo detalle de la compra
	$do_compra_detalle = DB_DataObject::factory('compra_detalle');
	$do_compra_detalle -> whereAdd('detalle_compra_id = '.$_POST['id']);
	
	$do_producto = DB_DataObject::factory('producto');
	$do_tipo = DB_DataObject::factory('tipo');
	$do_categoria = DB_DataObject::factory('categoria');
	$do_producto_stock = DB_DataObject::factory('producto_stock');

	$do_categoria -> joinAdd($do_tipo,"LEFT");
	$do_producto -> joinAdd($do_categoria,"LEFT");
	$do_compra_detalle -> joinAdd($do_producto,"LEFT");
	$do_compra_detalle -> joinAdd($do_producto_stock,"LEFT");
	
	$do_compra_detalle -> find(); 

	while ($do_compra_detalle -> fetch()) { 

		$productos_dev[$do_compra_detalle -> detalle_id]['id'] = $do_compra_detalle -> detalle_prod_id;
		$productos_dev[$do_compra_detalle -> detalle_id]['det_id'] = $do_compra_detalle -> detalle_id;
		$productos_dev[$do_compra_detalle -> detalle_id]['cant'] = $do_compra_detalle -> detalle_prod_cant - $do_compra_detalle -> detalle_prod_dev;
		$productos_dev[$do_compra_detalle -> detalle_id]['nombre'] = $do_compra_detalle -> prod_nombre;
		$productos_dev[$do_compra_detalle -> detalle_id]['valor'] = $do_compra_detalle -> detalle_prod_precio_u;
	}

	echo json_encode($productos_dev);
	exit;
?>
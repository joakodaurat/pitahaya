<?php
 
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_prod = DB_DataObject::factory('producto');
	// print_r($_POST);exit;
	$do_productos = $do_prod -> getProductos($_POST['id']);
	$do_stock = $do_prod -> getStockPorTalle($_POST['id']);

	$resp['producto'] = $do_productos;
	$resp['stock'] = $do_stock;
	
	print_r(json_encode($resp));

?>
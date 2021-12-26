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
	$do_stocktotal = $do_prod -> getStockTotal($_POST['id']);

	// traigo el valor de dolar actual
	$do_ultima_caja = DB_DataObject::factory('caja');
	$ultima_caja = $do_ultima_caja -> getUltimaCaja();

	$resp['producto'] = $do_productos;
	$resp['stock'] = $do_stock;
	$resp['stocktotal'] = $do_stocktotal;
	$resp['valordolar'] = $ultima_caja -> caja_valor_dolar;
	
	print_r(json_encode($resp));

?>
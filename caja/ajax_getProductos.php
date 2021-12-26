<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	$do_productos = DB_DataObject::factory('producto');
	$do_productos -> whereAdd('prod_marca_id = '.$_POST['marca_id'].' AND prod_cat_id='.$_POST['categoria_id']);
	

	$do_productos -> find();
	//print_r($do_productos);exit;

	while ($do_productos -> fetch()) {
		$productos[$do_productos -> prod_id]['prod_id'] = $do_productos -> prod_id;
		$productos[$do_productos -> prod_id]['prod_nombre'] = $do_productos -> prod_nombre;
		$productos[$do_productos -> prod_id]['prod_precio'] = $do_productos -> prod_precio;
		$productos[$do_productos -> prod_id]['prod_imagen'] = $do_productos -> prod_img1;

	}

	// traigo el valor de dolar actual
	$do_ultima_caja = DB_DataObject::factory('caja');
	$ultima_caja = $do_ultima_caja -> getUltimaCaja();
	$valordolar = $ultima_caja -> caja_valor_dolar;
	
	require_once('public/productos.html');
	exit;
?>
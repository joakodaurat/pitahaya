<?php
	require_once('../config/web.config');
	//require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	session_start();

// --------------------------------	ABM ------------------------------------ //

	//var_dump(count($_SESSION['carro']));die();

    //var_dump($_SESSION['carro']);die();


// --------------------------------	OBJETOS ------------------------------------ //
	$do_categorias = DB_DataObject::factory('categoria');
	$categorias = $do_categorias -> getCategorias();
	$cat = array();
    //print_r($pago);exit;
	while ($categorias -> fetch()) { 
		$cat[$categorias -> cat_id]['id'] = $categorias -> cat_id;
		$cat[$categorias -> cat_id]['nombre'] = $categorias -> cat_nombre;
	}
// -------------------------------- Template ------------------------------------ //
	require_once('public/header.html');
	require_once('public/product.html');

	exit;
?>
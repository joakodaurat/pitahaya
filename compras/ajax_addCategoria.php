<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	$do_categorias = DB_DataObject::factory('categoria');
	$do_categorias -> cat_nombre = $_POST['nombre'];
	$id = $do_categorias -> insert();

	echo(json_encode($id));
?>
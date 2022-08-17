<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	$producto = DB_DataObject::factory('producto');
	$id_prod_add = $producto -> nuevoProducto($_POST);

	$categoria = DB_DataObject::factory('categoria');
	$cat = $categoria -> getCategorias($_POST['input_categoria']);

	$resp['id_prod'] = $id_prod_add;
	$resp['nom_prod'] = $_POST['input_codigo'] .' | '.$cat -> cat_nombre .' | '.$mar -> marca_nombre.' | '.$_POST['input_modelo'];

	echo(json_encode($resp));
?>
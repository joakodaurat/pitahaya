<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	$do_marcas = DB_DataObject::factory('marca');
	$do_marcas -> marca_nombre = $_POST['nombre'];

	if($do_marcas -> find(true)){
		$respuesta = $do_marcas -> marca_id;
	}else{
		$respuesta = false;
	}
	echo(json_encode($respuesta));
?>
<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$prem = $_POST['premium'];
 
	$do_marcas = DB_DataObject::factory('marca');
	$do_marcas -> marca_id = $_POST['id'];
	$do_marcas -> find(true);



	require_once('public/modales/edit_marca.html');
	exit;
?>
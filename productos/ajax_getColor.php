<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$prem = $_POST['premium'];
 
	$do_colores = DB_DataObject::factory('color');
	$do_colores -> color_id = $_POST['id'];
	$do_colores -> find(true);



	require_once('public/modales/edit_color.html');
	exit;
?>
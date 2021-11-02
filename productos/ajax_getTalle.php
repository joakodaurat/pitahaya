<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$prem = $_POST['premium'];
 
	$do_talles = DB_DataObject::factory('talle');
	$do_talles -> talle_id = $_POST['id'];
	$do_talles -> find(true);



	require_once('public/modales/edit_talle.html');
	exit;
?>
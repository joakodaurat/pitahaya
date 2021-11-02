<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	$do_inversion = DB_DataObject::factory('inversiones');
	$do_inversion -> inversion_concepto = $_POST['concepto'];
	$do_inversion -> inversion_monto = $_POST['monto'];
	$do_inversion -> inversion_fh = date("Y-m-d H:i:s");
	$id = $do_inversion -> insert();
	$fecha = date("H:i:s");

	echo(json_encode($id));
?>

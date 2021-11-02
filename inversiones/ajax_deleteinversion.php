<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	$do_inversion = DB_DataObject::factory('inversiones');
	$do_inversion -> inversion_id  = $_POST['id_delete_inversion'];
    $do_inversion -> find(true);
    $do_inversion -> delete();
	

	echo(json_encode($_POST['id_delete_inversion']));
?>

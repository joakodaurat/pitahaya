<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual;

	$inversiones = DB_DataObject::factory('inversiones');
	$do_inversiones = $inversiones -> getinversiones($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
	//print_r($do_inversiones);exit;



	require_once('public/listado_inversiones.html');
	exit;
?>

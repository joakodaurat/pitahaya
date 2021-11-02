<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 month");

	$inversiones = DB_DataObject::factory('inversiones');

	if(!$_GET['fecha_desde']){
		$do_inversiones = $inversiones -> getInversiones($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	} else {
		$do_inversiones =  $inversiones -> getinversiones($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}


	require_once('public/historial_inversiones.html');
	exit;
?>

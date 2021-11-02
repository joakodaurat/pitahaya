<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 month");

	$notas = DB_DataObject::factory('notas');

	if(!$_GET['fecha_desde']){
		$do_notas = $notas -> getNotas(date('Y-m-d',$f_desde),date('Y-m-d'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	} else {
		$do_notas = $notas -> getNotas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}

    $notas -> orderBy('nota_id DESC');
	$notas -> find();

	require_once('public/listado_notas.html');
	exit;
?>

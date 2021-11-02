<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	 if($_POST['nuevo_concepto']) {
	 	$concepto = DB_DataObject::factory('venta_concepto');
     	$id = $concepto -> nuevoConcepto($_POST);
		header("Location: despachadas.php?id_concepto=".$id."&id_venta=".$_POST['concepto_venta_id']);
	}
	

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 month");

	$do_concepto = DB_DataObject::factory('venta_concepto_tipo');
	$do_concepto -> find();

	$venta = DB_DataObject::factory('venta');

	if(!$_GET['fecha_desde']){
		$do_ventas = $venta -> getVentasArchivadas($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	} else {
		$do_ventas = $venta -> getVentasArchivadas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}
	require_once('public/archivadas.html');
	exit;
?>

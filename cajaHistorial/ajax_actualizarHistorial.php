<?php
	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// $prem = $_POST['premium'];



	if(!$_POST['fecha']) {
		$fecha_filtro = date('Y-m-d');
	} else {
		$fecha_filtro = DateTime::createFromFormat('d/m/Y',$_POST['fecha'])->format('Y-m-d');
	}

	$do_caja = DB_DataObject::factory('caja');	
	$aperturasDiarias = $do_caja -> getAperturasDiarias($fecha_filtro);

	$caja = DB_DataObject::factory('caja');	
	$aperturas = $caja -> getAperturasDiarias($fecha_filtro);
	$aperturas -> find(true);

	$usuario = DB_DataObject::factory('usuario');


	require_once('public/datosHistorial.html');
	exit;
?>
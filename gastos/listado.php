<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	//print_r($_POST);exit;
	if($_POST['add_gasto']) {
		$gasto = DB_DataObject::factory('gasto');
		$gasto -> gasto_fh = date('Y-m-d H:i:s');
		$gasto -> gasto_concepto = $_POST['input_concepto'];
		$gasto -> gasto_monto_total = $_POST['input_monto'];
		$gasto -> gasto_usuario_id = $_SESSION['usuario']['id'];

		$gasto -> gasto_observacion = $_POST['input_observacion'];
		

		$id = $gasto -> insert();

		
		header("Location: listado.php?id_gasto=".$id);  
	}

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 month");

	$do_gastos = DB_DataObject::factory('gasto');
	$do_usrs = DB_DataObject::factory('usuario');

	$do_gastos -> joinAdd($do_usrs);
	$do_gastos -> orderBy('gasto_id DESC');
	
	$do_gastos -> find();

	$tipo_gasto = DB_DataObject::factory('tipo_gasto');
	$tipo_gasto -> whereAdd('tg_baja = 0');
	$tipo_gasto -> find();

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	require_once('public/listado_gastos.html');
	exit;
?>

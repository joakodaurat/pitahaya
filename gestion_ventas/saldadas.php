<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	if($_POST['nueva_devolucion']) {
		$devolucion = DB_DataObject::factory('devolucion');
		$datos_dev = $devolucion -> nuevaDevolucion($_POST);
		header("Location: saldadas.php?id_devolucion=".$_POST['edit_venta_id'].'&busqueda='.$_POST['campo_busqueda']);
	}

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 week");

	$venta = DB_DataObject::factory('venta');

	if(!$_GET['fecha_desde']){
		$do_ventas = $venta -> getVentasSaldadas($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	} else {
		$do_ventas = $venta -> getVentasSaldadas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}

	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_baja = 0;
	$do_cli -> find();
	
	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
		$clientes[$do_cli -> cliente_id]['dni'] = $do_cli -> cliente_dni;
	}

	require_once('public/saldadas.html');
	exit;
?>

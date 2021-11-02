<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 month");

	$pago_proveedor = DB_DataObject::factory('pago_proveedor');

	if(!$_GET['fecha_desde']){
		$do_pagos = $pago_proveedor -> getPagos($f_desde -> format('Y-m-d'),date('Y-m-d'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	} else {
		$do_pagos = $pago_proveedor -> getPagos($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}


	//PARA MODAL NUEVO PAGO
	if($_POST['nuevo_pago']) {
		$pago = DB_DataObject::factory('pago_proveedor');
		$id = $pago -> nuevoPago($_POST);
		//print_r($_POST);exit;
		header("Location: listado.php?id_pago=".$id); 
	}

	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> prov_baja = 0;
	$do_prov -> find();

	$proveedores = array();

	while ($do_prov -> fetch()) { 
		$proveedores[$do_prov -> prov_id]['id'] = $do_prov -> prov_id;
		$proveedores[$do_prov -> prov_id]['nombre'] = $do_prov -> prov_nombre;
	}


	$do_banco = DB_DataObject::factory('banco');
	$do_banco -> banco_baja = 0;
	$do_banco -> find();

	$do_banco_et = DB_DataObject::factory('banco');
	$do_banco_et -> banco_baja = 0;
	$do_banco_et -> find();

	$do_banco_rt = DB_DataObject::factory('banco');
	$do_banco_rt -> banco_baja = 0;
	$do_banco_rt -> find();

	$do_banco_d = DB_DataObject::factory('banco');
	$do_banco_d -> banco_baja = 0;
	$do_banco_d -> find();

	$do_banco_ch3 = DB_DataObject::factory('banco');
	$do_banco_ch3 -> banco_baja = 0;
	$do_banco_ch3 -> find();

	$do_cheques_terceros = DB_DataObject::factory('cheque');
	$do_cheques_terceros -> joinAdd($do_banco_ch3);
	$do_cheques_terceros -> whereAdd('cheque_estado IN (1,2)');	// Pendiente de cobro o vencido
	$do_cheques_terceros -> find();
	//FIN PARA MODAL NUEVO PAGO

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();
	
	require_once('public/listado_pagos.html');
	exit;
?>

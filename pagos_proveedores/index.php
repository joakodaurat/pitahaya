<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	if($_POST['nuevo_pago']) {
		$pago = DB_DataObject::factory('pago_proveedor');
		$id = $pago -> nuevoPago($_POST);
		//print_r($_POST);exit;
		header("Location: index.php?id_pago=".$id); 
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
	
	require_once('public/index.html');
	exit;
?>

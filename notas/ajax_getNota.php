<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_nota = DB_DataObject::factory('notas');
	$do_nota -> whereAdd('nota_id = '.$_POST['id']);

	$do_cliente = DB_DataObject::factory('cliente');
	$do_usuario = DB_DataObject::factory('usuario');
	$do_proveedores = DB_DataObject::factory('proveedor');

	$do_nota -> joinAdd($do_usuario,"LEFT");
	$do_nota -> joinAdd($do_cliente,"LEFT");
	$do_nota -> joinAdd($do_proveedores,"LEFT");

	$do_nota -> find(true);

	
	require_once('public/modales/ver_nota.html');
	exit;
?>
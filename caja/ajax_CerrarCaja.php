<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$mail = DB_DataObject::factory('mail');
	$do_caja = DB_DataObject::factory('caja');
	
	$id = $do_caja -> cerrarCaja($_POST);
	$respuesta = $mail -> enviar_caja_mail($_POST);

	


	$do_caja -> caja_id = $_POST['id'];

	return $respuesta;

	exit;
?>
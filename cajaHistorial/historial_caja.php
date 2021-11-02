<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$usr = DB_DataObject::factory('usuario');
	$usr -> usua_id = $_SESSION['usuario']['id'];
	$usr -> find(true);

	$premium = $usr -> esPremium();
	


	$fecha_actual = new DateTime();	
	$fecha_actual -> modify("+1 day");
	$fecha_actual = date_format($fecha_actual,'Y-m-d');



	require_once('public/cajaHistorial.html');
	exit;
?>

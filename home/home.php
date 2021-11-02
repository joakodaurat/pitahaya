<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	// PEAR
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	
	$_SESSION['app_id'] = APP_ID;

	if($_POST['config_usuario_id']){
		configurar_user($_POST);
	}

	$activo['Inicio'] = 'active';

	$usr = DB_DataObject::factory('usuario');
	$usr -> usua_id = $_SESSION['usuario']['id'];
	$usr -> find(true);
	$vendedor = $usr -> esVendedor();

	if($vendedor){
		header("Location: ../caja/index.php");
	}else {
		require_once('../templates/templates/index.html');
	}

	
	exit;
?>
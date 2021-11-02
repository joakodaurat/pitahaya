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
	$permiso = getPermisos($_SESSION['app_id'], $_SESSION['modulo_id'], $_SESSION['usuario']['id']);
	//DB_DataObject::debugLevel(5);

	if($_POST['edit_marca']) {// Editar marca
		//print_r($_POST);exit;
		$do_marca = DB_DataObject::factory('marca');
		$edit_marca_id = $do_marca -> edit_marca($_POST);
		header("Location: marcas.php?id_marca=".$edit_marca_id);

	}
	if($_POST['add_marca']) { // Alta marca
		//print_r($_POST);exit;
		$do_marca = DB_DataObject::factory('marca');
		$id = $do_marca -> alta_marca($_POST);
		header("Location: marcas.php?id_nuevo=".$id);

	}

	$do_marcas = DB_DataObject::factory('marca');
	$do_marcas = $do_marcas -> getMarcas();

	require_once('public/listado_marcas.html');
	exit;
?>

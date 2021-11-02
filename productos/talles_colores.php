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

	if($_POST['edit_talle']) {// Editar talle
		//print_r($_POST);exit;
		$do_talle = DB_DataObject::factory('talle');
		$id_talle_edit = $do_talle -> edit_talle($_POST);
		header("Location: talles_colores.php");

	}
	if($_POST['add_talle']) { // Alta talle
		//print_r($_POST);exit;
		$do_talle = DB_DataObject::factory('talle');
		$id = $do_talle -> alta_talle($_POST);
		header("Location: talles_colores.php?id_nuevo=".$id);

	}

	$do_talles = DB_DataObject::factory('talle');
	$do_talles = $do_talles -> getTalles();

	if($_POST['edit_color']) {// Editar color
		//print_r($_POST);exit;
		$do_color = DB_DataObject::factory('color');
		$id_color_edit = $do_color -> edit_color($_POST);
		header("Location: talles_colores.php");

	}
	if($_POST['add_color']) { // Alta color
		//print_r($_POST);exit;
		$do_color = DB_DataObject::factory('color');
		$id = $do_color -> alta_color($_POST);
		header("Location: talles_colores.php?id_nuevo=".$id);

	}

	$do_colores = DB_DataObject::factory('color');
	$do_colores = $do_colores -> getColores();

	require_once('public/listado_talles_colores.html');
	exit;
?>

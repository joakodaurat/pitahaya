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

	if($_POST['prod_id']) {// Editar prod
		//print_r($_POST);exit;
		$producto = DB_DataObject::factory('producto');
		$id_prod_edit = $producto -> modificarPrecio($_POST);
		header("Location: productos.php?id_edit=".$id_prod_edit);
	}

	if($_POST['add_producto']) {// Nuevo prod
		//print_r($_FILES);exit;
		$producto = DB_DataObject::factory('producto');
		$id_prod_add = $producto -> nuevoProducto($_POST,$_FILES);
		header("Location: productos.php?id_add=".$id_prod_add);
	}

	if($_POST['modificar_precio'] == 1) {// Cambiar precios
		//print_r($_POST);exit;
		$producto = DB_DataObject::factory('producto');
		$SeModifico = $producto -> modificarPrecios($_POST);
		header("Location: productos.php?modifico=".$SeModifico);
		
	}

	$permiso = getPermisos($_SESSION['app_id'], $_SESSION['modulo_id'], $_SESSION['usuario']['id']);

	$producto = DB_DataObject::factory('producto');
	$productos = $producto -> getProductos();

	$do_categoria = DB_DataObject::factory('categoria');
	$do_categoria -> cat_baja = 0;
	$do_categoria -> find();

	$do_marca = DB_DataObject::factory('marca');
	$do_marca -> cat_baja = 0;
	$do_marca -> find();

	$do_marcas = $do_marca -> getMarcas();
	$categorias = $do_categoria -> getCategorias();

	require_once('public/listado_productos.html');
	exit;
?>

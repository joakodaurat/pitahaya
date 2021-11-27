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
	//	print_r($_FILES);exit;
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

	$do_productos = DB_DataObject::factory('producto');

	$talles = DB_DataObject::factory('talle');
	$talles -> find();


// PARA LOS MODALES DE MODIFICAR STOCK
	//entradastocktienda
    if($_POST['nueva_compra']) {
		//print_r($_POST);exit;
		$compra = DB_DataObject::factory('compra');
		$id = $compra -> nuevaCompra($_POST);
		header("Location: productos.php?id_compra=".$id); 
	}
	//salidatocktienda
	if($_POST['nueva_salida']) {
		$compra = DB_DataObject::factory('compra');
		$id = $compra -> nuevaSalida($_POST);
		header("Location: productos.php?id_salida=".$id);
	}
	//entradastockbodega
	if($_POST['entrada_bodega']) {
		//print_r($_POST);exit;
		$compra = DB_DataObject::factory('compra');
		$id = $compra -> nuevaentradabodega($_POST);
		header("Location: productos.php?id_compra=".$id); 
	}
	//salidastockbodega
		if($_POST['salida_bodega']) {
		$compra = DB_DataObject::factory('compra');
		$id = $compra -> nuevaSalidaBodega($_POST);
		header("Location: productos.php?id_salida=".$id);
	}


	$talle = DB_DataObject::factory('talle');
	$talles = $talle -> getTallesJson();	

	$do_prod = DB_DataObject::factory('producto');

	$listado_productos = $do_prod -> getproductos();
	$productos_modificar_stock = array();

	while ($listado_productos -> fetch()) { 
		$productos_modificar_stock[$listado_productos -> prod_id]['id'] = $listado_productos -> prod_id;
		$productos_modificar_stock[$listado_productos -> prod_id]['modelo'] = $listado_productos -> prod_codigo .' - '. utf8_decode($listado_productos -> cat_nombre).' | '. utf8_decode($listado_productos -> marca_nombre).' | '. utf8_decode($listado_productos -> prod_nombre);
	}

	


	require_once('public/listado_productos.html');
	exit;
?>

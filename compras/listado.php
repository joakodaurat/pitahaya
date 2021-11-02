<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	if($_POST['nueva_compra']) {
		//print_r($_POST);exit;
		$compra = DB_DataObject::factory('compra');
		$id = $compra -> nuevaCompra($_POST);
		header("Location: listado.php?id_compra=".$id); 
	}

	if($_POST['nuevo_pago']) {
		$pago = DB_DataObject::factory('pago_proveedor');
		$id = $pago -> nuevoPago($_POST);

		$c = DB_DataObject::factory('compra');
		$c -> compra_id = $_POST['compra_id'];
		$c -> find(true);
		$c -> compra_estado_id = 2;
	
		$c -> update();
		header("Location: listado.php?id_pago=".$id."&id_compra_abrir=".$_POST['compra_id']);
	}

	if($_POST['nuevo_concepto']) {
		$concepto = DB_DataObject::factory('compra_concepto');
		$id = $concepto -> nuevoConcepto($_POST);

		header("Location: listado.php?id_concepto=".$id."&id_compra_abrir=".$_POST['concepto_compra_id']);
	}

	$do_prod = DB_DataObject::factory('producto');

	$listado_productos = $do_prod -> getproductos();
	$productos = array();

	while ($listado_productos -> fetch()) { 
		$productos[$listado_productos -> prod_id]['id'] = $listado_productos -> prod_id;
		$productos[$listado_productos -> prod_id]['modelo'] = $listado_productos -> prod_codigo .' - '. utf8_decode($listado_productos -> cat_nombre).' | '. utf8_decode($listado_productos -> marca_nombre).' | '. utf8_decode($listado_productos -> prod_nombre);
	}

	// print_r($listado_productos);

	$do_cate = DB_DataObject::factory('categoria');
	$do_categorias = $do_cate -> getCategorias();
	$do_categorias_edit = $do_cate -> getCategorias();
	//PROVEEDORES
	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> prov_baja = 0;
	$do_prov -> find();

	$proveedores = array();

	while ($do_prov -> fetch()) { 
		$proveedores[$do_prov -> prov_id]['id'] = $do_prov -> prov_id;
		$proveedores[$do_prov -> prov_id]['nombre'] = $do_prov -> prov_nombre;
	}

	//PROVEEDORES

	$do_prov = DB_DataObject::factory('proveedor');
	$do_prov -> prov_baja = 0;
	$do_prov -> find();



	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 month");

	$compra = DB_DataObject::factory('compra');

	if(!$_GET['fecha_desde']){
		$do_compras = $compra -> getCompras($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	} else {
		$do_compras = $compra -> getCompras($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}


	/* Stocky */

	$talle = DB_DataObject::factory('talle');
	$talles = $talle -> getTallesJson();	

	$color = DB_DataObject::factory('color');
	$colores = $color -> getColoresJson();

	$do_concepto = DB_DataObject::factory('compra_concepto_tipo');
	$do_concepto -> find();

	$do_categoria = DB_DataObject::factory('categoria');
	$do_categoria -> cat_baja = 0;
	$do_categoria -> find();

	$do_marca = DB_DataObject::factory('marca');
	$do_marca -> cat_baja = 0;
	$do_marca -> find();


	require_once('public/listado_compras.html');
	exit;
?>

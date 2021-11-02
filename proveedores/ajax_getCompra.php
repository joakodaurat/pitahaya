<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_compras = DB_DataObject::factory('compra');
	$do_compras -> whereAdd('compra_id = '.$_POST['id']);
	$do_proveedor = DB_DataObject::factory('proveedor');

	$do_compras -> joinAdd($do_proveedor,"LEFT");

	$do_compras -> find(true);

	$proveedores = array();
	$proveedores['nombre'] = $do_compras -> prov_nombre;


	$respuesta = array();

	$respuesta['compra'] = $do_compras;

	// Traigo detalle de la compra
	$do_compras_detalle = DB_DataObject::factory('compra_detalle');
	$do_compras_detalle -> whereAdd('detalle_compra_id = '.$do_compras -> compra_id);
	
	$do_producto = DB_DataObject::factory('producto');
	
	$do_marca = DB_DataObject::factory('marca');
	$do_categoria = DB_DataObject::factory('categoria');
	
	$do_producto -> joinAdd($do_marca);
	$do_producto -> joinAdd($do_categoria);

	$do_compras_detalle -> joinAdd($do_producto);
	
	$do_compras_detalle -> find(); 

	while ($do_compras_detalle -> fetch()) { 
		// print_r($do_compras_detalle);exit;
		$detalle[$do_compras_detalle -> detalle_id]['cat_nombre'] = $do_compras_detalle -> cat_nombre;
		$detalle[$do_compras_detalle -> detalle_id]['tipo_nombre'] = $do_compras_detalle -> tipo_desc;
		$detalle[$do_compras_detalle -> detalle_id]['prod_id'] = $do_compras_detalle -> prod_id;  
		$detalle[$do_compras_detalle -> detalle_id]['prod_modelo'] = $do_compras_detalle -> prod_codigo .' - '. utf8_decode($do_compras_detalle -> cat_nombre).' | '. utf8_decode($do_compras_detalle -> marca_nombre).' | '. utf8_decode($do_compras_detalle -> prod_nombre); 
		$detalle[$do_compras_detalle -> detalle_id]['color'] = $do_compras_detalle -> detalle_prod_color; 
		$detalle[$do_compras_detalle -> detalle_id]['talle'] = $do_compras_detalle -> detalle_prod_talle; 
		$detalle[$do_compras_detalle -> detalle_id]['cant'] = $do_compras_detalle -> detalle_prod_cant; 
		$detalle[$do_compras_detalle -> detalle_id]['precio_por_kg'] = $do_compras_detalle -> detalle_prod_precio_u; 
		$detalle[$do_compras_detalle -> detalle_id]['marca'] = $do_compras_detalle -> ct_id;
		$detalle[$do_compras_detalle -> detalle_id]['precio_parcial'] = $do_compras_detalle -> detalle_prod_cant * $do_compras_detalle -> detalle_prod_precio_u;
	}

	// $do_conceptos = DB_DataObject::factory('compra_concepto');
	// $do_conceptos -> whereAdd('cc_compra_id = '.$_POST['id']);
	// $do_conceptos -> find();

	$talle = DB_DataObject::factory('talle');
	$talles = json_decode($talle -> getTallesJson());	

	$color = DB_DataObject::factory('color');
	$colores = json_decode($color -> getColoresJson());

	require_once('public/modales/ver_compra.html');
	exit;
?>
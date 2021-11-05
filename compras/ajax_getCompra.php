<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_compras = DB_DataObject::factory('compra');
	$do_compras -> whereAdd('compra_id = '.$_POST['id']);
	
	$do_usuario = DB_DataObject::factory('usuario');
	$do_compras -> joinAdd($do_usuario);
	
	$do_compras -> find(true);	
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
	// print_r($do_compras_detalle);exit;
	$detalle = array();
	while ($do_compras_detalle -> fetch()) { 
		// print_r($do_compras_detalle);exit;
		$detalle[$do_compras_detalle -> detalle_id]['cat_nombre'] = $do_compras_detalle -> cat_nombre;
		$detalle[$do_compras_detalle -> detalle_id]['tipo_nombre'] = $do_compras_detalle -> tipo_desc;
		$detalle[$do_compras_detalle -> detalle_id]['prod_id'] = $do_compras_detalle -> prod_id;  
		$detalle[$do_compras_detalle -> detalle_id]['prod_modelo'] = $do_compras_detalle -> prod_codigo .' - '. utf8_decode($do_compras_detalle -> cat_nombre).' | '. utf8_decode($do_compras_detalle -> marca_nombre).' | '. utf8_decode($do_compras_detalle -> prod_nombre);  
		$detalle[$do_compras_detalle -> detalle_id]['talle'] = $do_compras_detalle -> detalle_prod_talle; 
		$detalle[$do_compras_detalle -> detalle_id]['cant'] = $do_compras_detalle -> detalle_prod_cant; 
		$detalle[$do_compras_detalle -> detalle_id]['precio_por_kg'] = $do_compras_detalle -> detalle_prod_precio_u; 
		$detalle[$do_compras_detalle -> detalle_id]['marca'] = $do_compras_detalle -> ct_id;
		$detalle[$do_compras_detalle -> detalle_id]['precio_parcial'] = $do_compras_detalle -> detalle_prod_cant * $do_compras_detalle -> detalle_prod_precio_u;
	}


	/* EDITAR */
	
	$do_prod = DB_DataObject::factory('producto');

	$listado_productos = $do_prod -> getproductos();
	$productos = array();

	while ($listado_productos -> fetch()) { 
		if($listado_productos -> getStock()) {
			$productos[$listado_productos -> prod_id]['id'] = $listado_productos -> prod_id;
			$productos[$listado_productos -> prod_id]['categoria'] = $listado_productos -> cat_nombre;
			$productos[$listado_productos -> prod_id]['marca'] = $listado_productos -> marca_nombre;
			$productos[$listado_productos -> prod_id]['modelo'] = $listado_productos -> prod_nombre;
			$productos[$listado_productos -> prod_id]['precio'] = $listado_productos -> prod_precio;
		}
	}



	//TALLES Y COLORES
	$talle = DB_DataObject::factory('talle');
	$talles = json_decode($talle -> getTallesJson());	



	//CONCEPTOS
	$do_conceptos = DB_DataObject::factory('compra_concepto');
	$do_conceptos -> whereAdd('cc_compra_id = '.$_POST['id']);
	$do_conceptos -> find();


	//PAGOS
	$do_pagos_prov = DB_DataObject::factory('pago_proveedor');
	$do_pagos_prov -> pago_prov_id = $do_compras -> compra_prov_id;
	$do_pagos_prov -> pago_compra_id = $do_compras -> compra_id;
	$do_pagos_prov -> find();

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	require_once('public/modales/edit_compra.html');
	exit;
?>
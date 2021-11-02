<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$do_ventas = DB_DataObject::factory('venta');
	$do_ventas -> whereAdd('venta_id = '.$_POST['id']);

	$do_ventas -> find(true);

	$respuesta = array();

	$respuesta['venta'] = $do_ventas;

	// Traigo detalle de la venta
	$do_venta_detalle = DB_DataObject::factory('venta_detalle');
	$do_venta_detalle -> whereAdd('detalle_venta_id = '.$do_ventas -> venta_id);
	
	$do_producto = DB_DataObject::factory('producto');
	$do_marca = DB_DataObject::factory('marca');
	$do_categoria = DB_DataObject::factory('categoria');
	$do_color = DB_DataObject::factory('color');
	$do_talle = DB_DataObject::factory('talle');
	
	$do_producto -> joinAdd($do_marca,"LEFT");
	$do_producto -> joinAdd($do_categoria,"LEFT");
	$do_venta_detalle -> joinAdd($do_producto,"LEFT");
	$do_venta_detalle -> joinAdd($do_color,"LEFT");
	$do_venta_detalle -> joinAdd($do_talle,"LEFT");

	$do_venta_detalle -> find(); 

	while ($do_venta_detalle -> fetch()) { 
		if($do_venta_detalle -> detalle_prod_cant - $do_venta_detalle -> detalle_cant_devueltos > 0){
			$detalle[$do_venta_detalle -> detalle_id]['prod_id'] = $do_venta_detalle -> detalle_prod_id;
			$detalle[$do_venta_detalle -> detalle_id]['det_id'] = $do_venta_detalle -> detalle_id;
			$detalle[$do_venta_detalle -> detalle_id]['prod_codigo'] = $do_venta_detalle -> prod_codigo;
			$detalle[$do_venta_detalle -> detalle_id]['marca_nombre'] = $do_venta_detalle -> marca_nombre;
			$detalle[$do_venta_detalle -> detalle_id]['cat_id'] = $do_venta_detalle -> cat_id;
			$detalle[$do_venta_detalle -> detalle_id]['cat_nombre'] = $do_venta_detalle -> cat_nombre;
			$detalle[$do_venta_detalle -> detalle_id]['prod_nombre'] = $do_venta_detalle -> prod_nombre;
			$detalle[$do_venta_detalle -> detalle_id]['prod_talle'] = $do_venta_detalle -> talle_nombre;
			$detalle[$do_venta_detalle -> detalle_id]['prod_color'] = $do_venta_detalle -> color_nombre;
			$detalle[$do_venta_detalle -> detalle_id]['prod_cant'] = $do_venta_detalle -> detalle_prod_cant - $do_venta_detalle -> detalle_cant_devueltos;
			$porc = 1;
			if($do_ventas -> venta_descuento_porc) {
				$porc -= $do_ventas -> venta_descuento_porc / 100;
			}
			$detalle[$do_venta_detalle -> detalle_id]['prod_val'] = $do_venta_detalle -> detalle_prod_precio_u * $porc;
			$detalle[$do_venta_detalle -> detalle_id]['prod_tot'] = $do_venta_detalle -> detalle_prod_precio_u * $do_venta_detalle -> detalle_prod_cant * $porc;
		}
	}

	/* Listado productos */

	$do_prod = DB_DataObject::factory('producto');
	$listado_productos = $do_prod -> getProductos();
	$productos = array();

	while ($listado_productos -> fetch()) { 
		if($listado_productos -> getStock()){
			$productos[$listado_productos -> prod_id]['id'] = $listado_productos -> prod_id;
			$productos[$listado_productos -> prod_id]['valor'] = $listado_productos -> prod_precio;
			$productos[$listado_productos -> prod_id]['modelo'] = $listado_productos -> prod_codigo .' - '. utf8_decode($listado_productos -> cat_nombre).' | '. utf8_decode($listado_productos -> marca_nombre).' | '. utf8_decode($listado_productos -> prod_nombre); 
		}
	}

	require_once('public/modales/devolucion_venta.html');
	exit;
?>
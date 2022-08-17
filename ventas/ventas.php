<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("first day of this month");

	$ventas = DB_DataObject::factory('venta');


	if(!$_GET['fecha_desde']){
		$do_ventas = $ventas -> getVentas($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');


	} else {
		$do_ventas = $ventas -> getVentas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}
    $ventas_detalle = DB_DataObject::factory('venta_detalle');
    $do_vendedor = DB_DataObject::factory('usuario');


    // si tiene el select de vendedor, filtro
    if($_GET['vendedor']){
	  $do_ventas -> whereAdd('venta_usuario_id = '.$_GET['vendedor']);

	}

    $ventas_detalle -> joinAdd($do_ventas);
    $ventas_detalle -> joinAdd($do_vendedor);
    $ventas_detalle -> find();


 	$producto = DB_DataObject::factory('producto');
    $categoria = DB_DataObject::factory('categoria');


    $producto ->joinAdd($categoria, "LEFT");
    $producto -> find();	
    $ventas_detalle ->joinAdd($producto);
    $ventas_detalle -> find();

    // si tiene el select de marca, filtro

    //print_r($ventas_detalle);exit; 


    // traigo los vendedores para el select
   	$do_vendedor = DB_DataObject::factory('usuario');
	

	$do_usua_roles = DB_DataObject::factory('usuario_rol');
    $do_roles = DB_DataObject::factory('rol');
  //  $do_roles -> rol_id = 5;
   // $do_roles->find(true);
    $do_usua_roles->joinAdd($do_roles);
    $do_vendedor->joinAdd($do_usua_roles);

 //   $do_vendedor -> rol_nombre = "vendedor";
    $do_vendedor->find();

  // print_r($venta_detalle);exit;


	$vendedores = array();

	while ($do_vendedor -> fetch()) { 
		$vendedores[$do_vendedor -> usua_id]['id'] = $do_vendedor -> usua_id;
		$vendedores[$do_vendedor -> usua_id]['nombre'] = $do_vendedor -> usua_nombre;
	}

 	// FIN traigo los vendedores para el select
 	// traigo las marcas para el select
   	$do_marca = DB_DataObject::factory('marca');
   	$do_marca->find();
 	// FIN traigo las marcas para el select



	require_once('public/ventas.html');
	exit;
?>
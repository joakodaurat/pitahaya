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
	$mail = DB_DataObject::factory('mail');
	$do_caja = DB_DataObject::factory('caja');
	$do_ultima_caja = DB_DataObject::factory('caja');
	$ultima_caja = $do_ultima_caja -> getUltimaCaja();

	$do_vendedor = DB_DataObject::factory('usuario');
	$do_vendedor -> usua_baja = 0;
	

	$do_usua_roles = DB_DataObject::factory('usuario_rol');
    $do_roles = DB_DataObject::factory('rol');
   // $do_roles -> rol_id = 5; // solo los que tienen rol de vendedor
   // $do_roles->find(true);
    $do_usua_roles->joinAdd($do_roles);
    $do_vendedor->joinAdd($do_usua_roles);

   // $do_vendedor -> rol_nombre = "vendedor";
    $do_vendedor->find();

  // print_r($do_vendedor);exit;


	$vendedores = array();

	while ($do_vendedor -> fetch()) { 
		$vendedores[$do_vendedor -> usua_id]['id'] = $do_vendedor -> usua_id;
		$vendedores[$do_vendedor -> usua_id]['nombre'] = $do_vendedor -> usua_nombre;
	}

	if($_POST['nuevaCaja']) {
		$id = $do_caja -> abrirCaja($_POST);
		header("Location: index.php");  
	}
	//cierro caja y envio mail
	if($_POST['cerrarCaja']) {
		$mail -> enviar_caja_mail($_POST);
		$id = $do_caja -> cerrarCaja($_POST);

		header("Location: index.php");  
	}
	if($_POST['eliminarVenta']) {
		$do_venta = DB_DataObject::factory('venta');
		$do_venta -> eliminarVenta($_POST);
	 
	}
	if($_POST['add_gasto']) {
		$gasto = DB_DataObject::factory('gasto');
		$gasto -> gasto_fh = date('Y-m-d H:i:s');
		$gasto -> gasto_concepto = $_POST['input_concepto'];
		$gasto -> gasto_monto_total = $_POST['input_monto'];
		$gasto -> gasto_usuario_id = $_SESSION['usuario']['id'];
		$gasto -> gasto_caja_id = $_POST['caja_id'];
		$id = $gasto -> insert();
		$ultima_caja -> caja_pagos_efectivo = $ultima_caja -> caja_pagos_efectivo - $_POST['input_monto'];
		$ultima_caja -> update();
		header("Location: index.php");  
	}
	if($_POST['eliminarGasto']) {
		$do_gasto = DB_DataObject::factory('gasto');
		$do_gasto -> gasto_id = $_POST['eliminar_gasto_id'];
        $do_gasto -> find(true);
        $do_gasto -> delete();

		$ultima_caja -> caja_pagos_efectivo = $ultima_caja -> caja_pagos_efectivo + $_POST['eliminar_gasto_monto'];
		$ultima_caja -> update();
		header("Location: index.php");
	 
	}

	$fecha_actual = new DateTime();	
	$f =  $fecha_actual -> modify("+1 day");
	$maniana = date_format($f,'Y-m-d');

	//para agregar nuevas ventas
	$do_prod = DB_DataObject::factory('producto');
	$listado_productos = $do_prod -> getProductos();
	$productos = array();

	while ($listado_productos -> fetch()) { 
		if($listado_productos -> getStock()){
			$productos[$listado_productos -> prod_id]['id'] = $listado_productos -> prod_id;
			$productos[$listado_productos -> prod_id]['modelo'] = $listado_productos -> prod_codigo .' - '. utf8_decode($listado_productos -> cat_nombre).' | '. utf8_decode($listado_productos -> marca_nombre).' | '. utf8_decode($listado_productos -> prod_nombre); 
		}
	}

	if($_POST['nueva_venta']) {
		// cargo el cobro
		$cobro = DB_DataObject::factory('cobro_cliente');
		$id_cobro = $cobro -> nuevoCobro($_POST);
		//cargo la venta
		$venta = DB_DataObject::factory('venta');
		$id = $venta -> nuevaVentaPorCaja($_POST,$id_cobro);

		
		header("Location: index.php?id_venta=".$id["id"]); 
	}

	$marcas = DB_DataObject::factory('marca');
	$marcas -> find();

	require_once('public/index.html');
	exit;
?>

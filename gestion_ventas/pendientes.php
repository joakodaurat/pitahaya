<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// if($_POST['Eliminar']) {
	// 	$venta_e = DB_DataObject::factory('venta');
	// 	$id_deleted = $venta_e -> eliminarVenta($_POST);
	// 	header("Location: pendientes.php?id_venta_elim=".$id_deleted.'&busqueda='.$_POST['campo_busqueda']);
	// }

	if($_POST['nuevo_cobro']) {
		$v = DB_DataObject::factory('venta');
		$v -> venta_id = $_POST['venta_id'];
		$v -> find(true);
		$v -> venta_estado_id = 2;
		$v -> venta_forma_pago_id = $_POST['combo_fpago'];

		$v -> update();

		$cobro = DB_DataObject::factory('cobro_cliente');
		$id = $cobro -> nuevoCobro($_POST);
		
		header("Location: pendientes.php?id_cobro=".$id); 
	}

	if($_POST['nueva_venta']) {
		$venta = DB_DataObject::factory('venta');
		$id = $venta -> nuevaVenta($_POST);
		header("Location: pendientes.php?id_venta=".$id["id"]); 
	}

	$do_prod = DB_DataObject::factory('producto');
	$listado_productos = $do_prod -> getProductos();
	$productos = array();

	while ($listado_productos -> fetch()) { 
		if($listado_productos -> getStock()){
			$productos[$listado_productos -> prod_id]['id'] = $listado_productos -> prod_id;
			$productos[$listado_productos -> prod_id]['modelo'] = $listado_productos -> prod_codigo .' - '. utf8_decode($listado_productos -> cat_nombre).' | '. utf8_decode($listado_productos -> marca_nombre).' | '. utf8_decode($listado_productos -> prod_nombre); 
		}
	}

	$do_cli = DB_DataObject::factory('cliente');
	$do_cli -> cliente_baja = 0;
	$do_cli -> find();

	$do_banco = DB_DataObject::factory('banco');
	$do_banco -> banco_baja = 0;
	$do_banco -> find();

	$do_banco2 = DB_DataObject::factory('banco');
	$do_banco2 -> banco_baja = 0;
	$do_banco2 -> find();

	$do_banco_et = DB_DataObject::factory('banco');
	$do_banco_et -> banco_baja = 0;
	$do_banco_et -> find();

	$do_banco_rt = DB_DataObject::factory('banco');
	$do_banco_rt -> banco_baja = 0;
	$do_banco_rt -> find();

	$do_banco_d = DB_DataObject::factory('banco');
	$do_banco_d -> banco_baja = 0;
	$do_banco_d -> find();

	$clientes = array();

	while ($do_cli -> fetch()) { 
		$clientes[$do_cli -> cliente_id]['id'] = $do_cli -> cliente_id;
		$clientes[$do_cli -> cliente_id]['nombre'] = $do_cli -> cliente_nombre;
		$clientes[$do_cli -> cliente_id]['dni'] = $do_cli -> cliente_dni;
	}

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("-1 day");

	$venta = DB_DataObject::factory('venta');

	if(!$_GET['fecha_desde']){
		$do_ventas = $venta -> getVentasPendientes($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');
	} else {
		$do_ventas = $venta -> getVentasPendientes($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}

	$caja = DB_DataObject::factory('caja');
	$cajaAbierta = $caja -> cajaAbiertaHoy();

	require_once('public/pendientes.html');
	exit;
?>

<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

 
	
	

	
	$do_productos = DB_DataObject::factory('producto');
    $do_productos -> prod_id = $_POST['idproducto'];
    $do_productos ->find(true);
    $do_productos -> prod_img1 = "../imagenes/sinimagen.PNG";
    $do_productos -> update();

    $borrar = @unlink($_POST['url']);
	
	exit;
?>
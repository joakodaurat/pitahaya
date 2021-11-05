<?php
 	header('Content-Type: application/json');

	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');
	$do_marcascategoria = DB_DataObject::factory('marca_categoria');
	$do_marcascategoria -> whereAdd('marcacat_marca_id = '.$_POST['id']);
	$marca_id = $_POST['id'];

	$do_marcascategoria -> find();


	while ($do_marcascategoria -> fetch()) { 
		$categorias[$do_marcascategoria -> marcacat_id]['categoria_id'] = $do_marcascategoria -> marcacat_categoria_id;
		$do_categoria = DB_DataObject::factory('categoria');
		$do_categoria -> whereAdd('cat_id = '.$do_marcascategoria -> marcacat_categoria_id);
		$do_categoria -> find(true);
		$categorias[$do_marcascategoria -> marcacat_id]['categoria_nombre'] = $do_categoria -> cat_nombre;
	}
	
	require_once('public/categorias.html');
	exit;
?>
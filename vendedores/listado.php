<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	// traigo usuarios vendedores y administradores de ventas
	$do_usuarios = DB_DataObject::factory('usuario');

    $do_usu_areas = DB_DataObject::factory('usuario_area');
	$do_areas = DB_DataObject::factory('area');
    $do_usu_areas->joinAdd($do_areas);

    $do_usuarios->joinAdd($do_usu_areas);

    $do_usua_roles = DB_DataObject::factory('usuario_rol');
    $do_roles = DB_DataObject::factory('rol');
    $do_usua_roles->joinAdd($do_roles);

    $do_usuarios->joinAdd($do_usua_roles);
    $do_usuarios->find();



 	if($_POST['add_vendedor']) {
		$do_usuario_add = DB_DataObject::factory('usuario');

		$do_usuario_add -> usua_nombre = $_POST['input_vendedor_nombre'];
		$do_usuario_add -> usua_usrid = $_POST['input_vendedor_nombre'];
		$do_usuario_add -> usua_pwd = md5($_POST['input_contraseña']);
		$do_usuario_add -> usua_tel1 = $_POST['input_vendedor_telefono'];
		$do_usuario_add -> usua_baja = 0;
		$id_usuario_add = $do_usuario_add -> insert();

		$do_usu_area_add = DB_DataObject::factory('usuario_area');
		$do_usu_area_add -> usarea_usua_id = $id_usuario_add;
		$do_usu_area_add -> usarea_area_id = 1; // lo inserto en el area de ventas
		$do_usu_area_add -> insert();

		$do_usua_rol_add = DB_DataObject::factory('usuario_rol');
		$do_usua_rol_add -> usrrol_usua_id = $id_usuario_add;
		$do_usua_rol_add -> usrrol_rol_id = 5;// con el rol de vendedor
		$do_usua_rol_add -> usrrol_app_id = 2;
		$do_usua_rol_add -> insert();
		header("Location: listado.php");
		}
		
	if($_POST['add_administrador']) {
		$do_usuario_add = DB_DataObject::factory('usuario');

		$do_usuario_add -> usua_nombre = $_POST['input_administrador_nombre'];
		$do_usuario_add -> usua_usrid = $_POST['input_administrador_nombre'];
		$do_usuario_add -> usua_pwd = md5($_POST['input_administrador_contraseña']);
		$do_usuario_add -> usua_tel1 = $_POST['input_administrador_telefono'];
		$do_usuario_add -> usua_baja = 0;
		$id_usuario_add = $do_usuario_add -> insert();

		$do_usu_area_add = DB_DataObject::factory('usuario_area');
		$do_usu_area_add -> usarea_usua_id = $id_usuario_add;
		$do_usu_area_add -> usarea_area_id = 1; // lo inserto en el area de ventas
		$do_usu_area_add -> insert();

		$do_usua_rol_add = DB_DataObject::factory('usuario_rol');
		$do_usua_rol_add -> usrrol_usua_id = $id_usuario_add;
		$do_usua_rol_add -> usrrol_rol_id = 6;// con el rol de administrador
		$do_usua_rol_add -> usrrol_app_id = 5;
		$do_usua_rol_add -> insert();
		header("Location: listado.php");
		}

	


	require_once('public/listado_vendedores.html');
	exit;
?>

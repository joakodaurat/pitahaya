<?php
	require_once(INC_PATH.'/debug_sistema.inc.php');	
    ini_set("session.gc_maxlifetime", 1000000);
	require_once(INC_PATH.'/pear.inc');
	session_name("hombres");
	session_start();
	function armarPermisos($do_usuario = null)
{
    $usuarios = array();
    
    while($do_usuario->fetch()){
        $usuarios['usuario']['id'] = $do_usuario->usua_id;
        $usuarios['usuario']['app_id'] = APP_ID;
        $usuarios['usuario']['nombre'] = $do_usuario->usua_usrid;
        $usuarios['usuario']['usua_nombre'] = $do_usuario->usua_nombre;
        $usuarios['usuario']['usua_email'] = $do_usuario->usua_email;
        if($do_usuario -> mod_baja != 1){
	        $usuarios['usuario']['permisos'][$do_usuario->mod_nombre][$do_usuario->tipoacc_id] = $do_usuario->tipoacc_nombre;
	        $usuarios['usuario']['permisos'][$do_usuario->modpag_scriptname][$do_usuario->tipoacc_id] = $do_usuario->tipoacc_nombre;
        }

    }
    //Fecha de acceso
    $usuarios['usuario']['fecha_acceso'] = date("Y-m-d H:i:s");
    $usuarios['usuario']['minutos_control'] = 10;
    //print_r($usuarios);exit;
    return $usuarios;
}

function calcular_tiempo_trasnc($hora1,$hora2){
    $separar[1]=explode(':',$hora1);
    $separar[2]=explode(':',$hora2);

    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1];
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1];
    $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2];
    return ($total_minutos_trasncurridos);

}
	Class AccesoIntranet {
		
		static function getIdUsuario() {
			return $_SESSION['usuario']['id'];
		}
		
		static function getMenuPrincipal() {
			require('../config/menu.config');
			//print_r($linkMenu);
			foreach($linkMenu as $l) {
	             if (AccesoIntranet::verificarAccesoPagina($l['link'],'Acceso')) {
	             	$linkMenu2[] = $l;
	      		}
	      	}	
	      	//print($linkMenu2);
	      	return $linkMenu2;        
		}
		
		static function getDatosUsuario() {
			return $_SESSION['usuario'];
		}
		/*
		static function getIdEmpresa() {
			return $_SESSION['usuario']['empresa'];
		}
		static function getNombreEmpresa() {
			return $_SESSION['usuario']['empresa_nombre'];
		}
		
		static function setIdEmpresa($id_empresa) {
			$_SESSION['usuario']['empresa'] = $id_empresa;
		}
		*/
		static function getAppId() {	
			if (isset($_SESSION['usuario']))
				return $_SESSION['usuario']['app_id'];
			else 
				return -1;
		}
		
		static function usuarioRegistrado($app_id) {	
			if (AccesoIntranet::getAppId() != $app_id) {
				session_destroy();
				session_name("hombres");
session_start();	
			}
			return isset($_SESSION['usuario']);
		}
		
		static function finalizarSesion() {
			session_name("hombres");
session_start();
			session_destroy();	
		}
		
		static function registrarUsuario($usuario,$clave,$app_id) {
			$do_usuario = DB_DataObject::factory('view_usuario_login');			
			if (PEAR::isError($do_usuario)) {
				trigger_error($do_usuario->getMessage(), E_USER_WARNING);
				return -1; 			
				exit;
			}	
			if($clave !== null)	
				$do_usuario->usua_pwd = md5($clave);
			$do_usuario->usua_usrid = $usuario;
			$do_usuario->app_id = $app_id;
			$encontrado = $do_usuario->find();
//print($usuario);exit;			
if($encontrado > 0) {
//print($app_id);exit;
				$sess_usuarios = armarPermisos($do_usuario);
				$_SESSION['usuario'] = $sess_usuarios['usuario'];
				//AccesoIntranet::registrarLog($sess_usuarios['usuario']['id'],$app_id);
				return true;
			}
			else {
				return false;
			}			
		}
		
		static function registrarLog($usua_id,$app_id) {
			$do_log_ing = DB_DataObject::factory('log_ingreso');
			$do_log_ing->loging_usua_id = $usua_id;
			$do_log_ing->loging_app_id = $app_id;
			$do_log_ing->loging_fecha = date('Y-m-d H:i:s');
			$do_log_ing->insert();	
		}

		static function registrarUsuarioDist($usuario,$clave,$app_id) {
			$do_usuario = DB_DataObject::factory('view_usuario_login');
			
			if (PEAR::isError($do_usuario)) {
				trigger_error($do_usuario->getMessage(), E_USER_WARNING);
				return -1; //array('usuario' => 'Error: Falla en acceso al sistema'); 			
				exit;
			}		
			$do_usuario->usua_pwd = md5($clave);
			$do_usuario->usua_usrid = $usuario;
			$do_usuario->app_id = $app_id;
			$encontrado = $do_usuario->find();
			if($encontrado > 0){
				 $id_usuario = $do_usuario->usua_id;
				 //$do_usuario_empresa = DB_DataObject::factory('usuario_empresa');
				 //$do_usuario_empresa -> usremp_usua_id = $do_usuario->usua_id;
				 //$encontrado2 = $do_usuario_empresa->find(true);
			}
					
			if($encontrado > 0){
				session_name("hombres");
session_start();
				
				$do_usuario = DB_DataObject::factory('view_usuario_login');
				$do_usuario->usua_id = $id_usuario;
	            $do_usuario->find();
				
				//Armo un array con los permisos, roles, etc que tiene el usuario
				$sess = armarPermisos($do_usuario);			
				$_SESSION['usuario'] = $sess['usuario'];
				
				//$do_usuario_empresa = DB_DataObject::factory('usuario_empresa');
                //$do_usuario_empresa -> usremp_usua_id = $do_usuario->usua_id;
                //$encontrado2 = $do_usuario_empresa->find(true);	
				
				AccesoIntranet::registrarLog(AccesoIntranet::getIdUsuario(),$app_id);				
				
				/*
				$_SESSION['usuario']['empresa'] = $do_usuario_empresa->usremp_emp_id;
				$do_empresa = DB_DataObject::factory('empresas');
				$do_empresa -> emp_id = $do_usuario_empresa->usremp_emp_id;
				if ($do_empresa->find(true))
				{
					$_SESSION['usuario']['empresa_tdist'] = $do_empresa-> emp_tdist_id;
					$_SESSION['usuario']['empresa_nombre'] = $do_empresa->emp_nombre;
				
				}*/		
				return true;
			}
			elseif ($encontrado <= 0){
				return -2; //array('usuario' => 'Usuario o clave no v&aacute;lida');
			}
			/*elseif ($encontrado2 <= 0){
				return -3; //array('usuario' => 'El usuario no tiene empresa asignada');
			} */
		}
		
		static function getAccesoArchivo($arch_id) {
			if (AccesoIntranet::getAccesoModulo('Superusuario'))
				return true;
			$do_ai = DB_DataObject::factory('archivos_importacion');
			$do_ai->arch_id = $arch_id;
			$do_ai->joinAdd(DB_DataObject::factory('tipo_archivo'));
			if (!$do_ai->find(true)) {
				return false;
			}
			$do_ddt = DB_DataObject::factory('diccionario_datos_tablas');
			$do_ddt->ddt_id = $do_ai->tipoarch_ddt_id;
			if (!$do_ddt->find(true))
				return false;
			return AccesoIntranet::getAccesoTabla($do_ddt->ddt_tabla_origen);
			exit;
		}
		
		static function getAccesoTabla($tabla) {
			if (AccesoIntranet::getAccesoModulo('Superusuario'))
				return true;
				
			if (!$tabla)
				return false;
			
			$do_ddt = DB_DataObject::factory('diccionario_datos_tablas');
			$do_ddt->ddt_tabla_origen = $tabla;
			if (!$do_ddt->find(true))
				return false;
				
			$do_mt = DB_DataObject::factory('modulo_tablas');		
			$do_mt->modtab_ddt_id = $do_ddt->ddt_id;
			
			$do_m = DB_DataObject::factory('modulo'); 
			$do_m->mod_app_id = APP_ID;				
			
			$do_m->joinAdd($do_mt);			
			
			$do_ur = DB_DataObject::factory('usuario_rol'); 
			$do_ur->usrrol_app_id = APP_ID;
			$do_ur->usrrol_usua_id = $_SESSION['usuario']['id'];
			
			$do_r = DB_DataObject::factory('rol'); 
			$do_r->joinAdd($do_ur);			
			
			$do_p = DB_DataObject::factory('permiso'); 
			$do_p->joinAdd($do_r);
			$do_p->joinAdd($do_m);
			return $do_p->find();
			exit;			
		}

		static function getAccesoSuperusuario() {							
			if (!AccesoIntranet::getDatosUsuario())
				return null;	
							
			$do_m = DB_DataObject::factory('modulo'); 
			$do_m->mod_nombre = 'Superusuario';
			$do_m->mod_app_id = APP_ID;				
			
			$do_ur = DB_DataObject::factory('usuario_rol'); 
			$do_ur->usrrol_app_id = APP_ID;
			$do_ur->usrrol_usua_id = $_SESSION['usuario']['id'];
			
			$do_r = DB_DataObject::factory('rol'); 
			$do_r->joinAdd($do_ur);			
			
			$do_p = DB_DataObject::factory('permiso'); 
			$do_p->joinAdd($do_r);
			$do_p->joinAdd($do_m);
			//DB_DataObject::debugLevel(5);
			return $do_p->find();
			exit;			
		}
				
		static function getAccesoModulo($modulo) {							
			if ((!$modulo) and (!AccesoIntranet::getDatosUsuario()))
				return null;	
			if (AccesoIntranet::getAccesoSuperusuario())
				return true;
			$do_m = DB_DataObject::factory('modulo'); 
			$do_m->mod_nombre = $modulo;
			$do_m->mod_app_id = APP_ID;	
			$do_m -> find();
			
			$do_ur = DB_DataObject::factory('usuario_rol'); 
			$do_ur->usrrol_app_id = APP_ID;
			$do_ur->usrrol_usua_id = $_SESSION['usuario']['id'];
			
			$do_r = DB_DataObject::factory('rol'); 
			$do_r->joinAdd($do_ur);			
			
			$do_p = DB_DataObject::factory('permiso'); 
			$do_p->joinAdd($do_r);
			$do_p->joinAdd($do_m);
			
			
			$do_p->find(true);
		//	print_r($do_p);	
//			exit;
			return ($do_p->N > 0);
			exit;			
		}

		static function getCodigoTiposArchivo() {	
			$archivos = '';	
			$obj = DB_DataObject::factory('parametros_aplicacion');				
			$archivos_datos = $obj->getValor('tipos_archivos_por_modulo','datos',true);
			$archivos_comercial = $obj->getValor('tipos_archivos_por_modulo','comercial',true);
			$archivos_servicio = $obj->getValor('tipos_archivos_por_modulo','servicio',true);
			$archivos_producto = $obj->getValor('tipos_archivos_por_modulo','producto',true);
			if (AccesoIntranet::getAccesoModulo('Superusuario')) {
				return $archivos_comercial.','.$archivos_servicio.','.$archivos_producto.','.$archivos_datos;
				exit;
			}
			$aux = AccesoIntranet::getAccesoModulo('Ver archivos de importación de comercial');			
			if($aux > 0)
				{
				$archivos .= $archivos_comercial;
				}
			$aux2 = AccesoIntranet::getAccesoModulo('Ver archivos de importación de producto');
			if($aux2 > 0)
				{
				$archivos .= $archivos_producto;
				}
			$aux3 = AccesoIntranet::getAccesoModulo('Ver archivos de importación de servicio');
			if($aux3 > 0)
				{
				$archivos .= $archivos_servicio;
				}	
			$aux4 = AccesoIntranet::getAccesoModulo('Ver archivos de importación de datos');
			if($aux4 > 0)
				{
				$archivos .= $archivos_datos;
				}	
			return $archivos;
		}
		
		static function accederWeb() {	
			
	//header('Location: ../home/mantenimiento.php');
			debug_sistema('Iniciando autorización');	
			
			if (MANTENIMIENTO == 1) {
				if (!AccesoIntranet::getAccesoModulo(utf8_encode('Mantenimiento')))
					header('Location: ../home/mantenimiento.php');
			}
			
			$pagina = $_SERVER['SCRIPT_NAME'];			
			if(VIRT_DIR) {
				$pagina = str_ireplace(VIRT_DIR,'',$pagina);
			}			
			if (WWW_DIR) {
				if (WWW_DIR!='expedientes')
					$pagina = str_ireplace(WWW_DIR,'',$pagina);
			}
			$pagina = trim($pagina,'/ ');
			
			debug_sistema('Autorizando Script '.$pagina);			
			$datosUsuario = AccesoIntranet::getDatosUsuario();	
			$aplicacionUsuario = $datosUsuario['app_id'];
			
			if ((!isset($datosUsuario)) or ($aplicacionUsuario != APP_ID)) {		
				session_destroy();
				session_name("hombres");
session_start();
				
				$_SESSION['pagina_originante'] = $_SERVER['REQUEST_URI'];
				if (!defined('POPUP')) {
					if (!isset($datosUsuario)) 
						debug_sistema('Usuario no logueado');
					else
						debug_sistema('Se ha cambiado de aplicacion');					
					
					header('Location: ../'.PGN_LOGIN);			
				}
				else {
					echo 'Debe estar logueado para ingresar a esta p&aacute;gina';
				}
				exit;
			}	
			else {
				//Si la diferencia entre que se valido y la actual es mayor a 10 minutos
				//le cargo nuevamente los permisos$
				debug_sistema('Usuario encontrado');
				
				$minutos = 0;
				$fecha_hora = explode(' ',$_SESSION['usuario']['fecha_acceso']);
				$minutos = calcular_tiempo_trasnc(date("H:i:s"),$fecha_hora[1]);
					 
				if($minutos > $_SESSION['usuario']['minutos_control']){
					debug_sistema('Tiempo de sesion vencido, refrescando permisos');		
					$do_usuario = DB_DataObject::factory('view_usuario_login');
					$do_usuario->usua_id = $datosUsuario['id'];
					if ($do_usuario->find()) {
						$sess_usuarios = armarPermisos($do_usuario);
						$_SESSION['usuario']['permisos']= $sess_usuarios['usuario']['permisos'];
						$_SESSION['usuario']['fecha_acceso'] = date("Y-m-d H:i:s");
					}
				}
				
				if (defined('PERMISOS')) //print_r(PERMISOS); exit;
					$accesos = explode(',', PERMISOS);
				else 
					$accesos = array('Acceso','Medio','Alto','Total');
				// print_r($accesos); exit;
				debug_sistema('La pagina requiere permisos de: '.implode(',',$accesos));		
					
				if (!defined('GENERICO')) {
					debug_sistema('Buscando permisos para la pÃ¡gina');		
					$habilitado = AccesoIntranet::verificarAcceso($datosUsuario['permisos'],$pagina,$accesos);
				}
				else {
					debug_sistema('La pÃ¡gina no requiere autorizacion');		
					$habilitado = true;
				}
			}
			
		   if (!$habilitado) {
				debug_sistema('Sin permisos para la pÃ¡gina la pÃ¡gina');		
				if (!defined('POPUP')) {
					header('Location: ../home/denegado.php');
				}
				else {
					echo "No tiene permiso para acceder a ".$_SESSION['no_autorizado'];
				}
				exit;
			}
			
			unset($aplicacionUsuario);
			unset($datosUsuario);
			unset($habilitado);
			unset($pagina);
		}
		
		static function verificarAccesoPagina($pagina = null, $acceso = array())
		{	$datosUsuario = AccesoIntranet::getDatosUsuario();
			if (is_array($acceso))
				$accesos = $acceso;
			else
				$accesos = array($acceso);
			//print_r($accesos);
			return AccesoIntranet::verificarAcceso($datosUsuario['permisos'],$pagina,$accesos);
		}
		
		static function mostarAccesoModulo($accesos = array(), $acceso = array())
		{
		//Permite el acceso al m�dulo	
			if(in_array($acceso, $accesos)){
				return 1;	
			}
			else{
				return 0;
			}
		}
		
		/**
		 * Verifica si tiene acceso o no a un modulo o pÃ¡gina
		 * @param <array> $permisos
		 * @param <string> $modulo
		 * @param <string> $pagina
		 * @param <string> $acceso
		 */
		static function verificarAcceso($permisos = array(),$pagina = null, $acceso = array())
		{


			if ($permisos['Todas'])
				return true;
			//print_r($acceso);		
			//Guardo de que pagina viene
			$_SESSION['no_autorizado'] = $pagina.' (Permisos requeridos: "'.implode('", "',$acceso).'")';
			//Primero no se permite
			$se_permite = 0;
			//Me fijo la cantidad de permisos que es necesaria
			$cant_permisos = count($acceso);
			 //si esta definida la pagina
			if(isset($permisos[$pagina])){
				foreach($acceso as $acc){
					//Pregunto si esta necesario en los permisos del usuario
					if(in_array($acc,$permisos[$pagina])){
						$se_permite++;
					}
				}
			}
			
			//Verifico si la cantidad de permisos necesaria es igual a la que tiene
			if($se_permite > 0){
				return true;
			}
			else{
				return false;
			}
		}
	}
?>

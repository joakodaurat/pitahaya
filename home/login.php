<?php	
	require_once('../config/web.config');
	require_once(CFG_PATH.'/data.config');
    require_once(INC_PATH.'/AccesoIntranet.class.php');	
	// librerias PEAR
	require_once('HTML/QuickForm.php');
	//DB_DataObject::debugLevel(5);	

	if (AccesoIntranet::usuarioRegistrado(APP_ID)) {
		header('Location: ../'.PGN_INDEX);
		exit;
	}	
	$frm = new HTML_QuickForm('login','post',$_SERVER['REQUEST_URI']);	

	$frm->addFormRule('esUsuario');
	if ($frm->validate()) {	
		if (isset($_SESSION['pagina_originante']))
			header('Location: '.$_SESSION['pagina_originante']);
	   	else 
	    header('Location: ../'.PGN_INDEX);
	    	exit;
	}
	
	require_once('../templates/templates/login.html');
	exit;
	
	function esUsuario($post) {		
		$encontrado = AccesoIntranet::registrarUsuario($post['usuario'],$post['clave'],APP_ID);	
		if ($encontrado === true) {			
			return true;
		}
        elseif ($encontrado == '-1'){
			return array('usuario' => 'Error: Falla en acceso al sistema');
		}
		else {
			return array('usuario' => 'Usuario o clave no v&aacute;lida');
        }
	}
?>

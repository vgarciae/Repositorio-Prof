<?php
	require_once("config.php");
	require_once(CUR_LIB_PATH."db.php");
	require_once(CUR_LIB_PATH."helper.php");
	require_once(CUR_LIB_PATH."template.php");

	sec_session_start();

	//echo login_check($mysqli)?"in":'out';
	if(!login_check($mysqli)){
	// No tenemos login del usuario
		if(isset( $_GET['action']) && $_GET['action']=='register'){
			if(isset($_POST['username'],$_POST['confirmpwd'])){
				//print_r($_POST);
				//var_dump($_POST);
				//die();
				register_user($mysqli,$_POST['username'],$_POST['email'],$_POST['p']);
			}
			else{
				$tmpl=new Template("register");
				
				$tmpl->set('title',getPageName('Registro de usuario'));
				$tmpl->set('wpath',getWebPath());
				$tmpl->set('msg',get_message());
							
				echo $tmpl->render();
			}
				
		}
		else{
			if(isset($_POST['email'],$_POST['p'])){
				
				$email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
				$password=$_POST['p'];
				
				if(login($email,$password,$mysqli)){
					header("Location: index.php?mensaje=hola");			
					exit();
				}
				else{
					set_message('danger','Correo electrónico o contraseña inválidos.');
					header("Location: login.php");
					exit();
				}
			}
			else{	
				$tmpl=new Template("login");
				$tmpl->set('title',getPageName('Inicio de sesión'));
				$tmpl->set('wpath',getWebPath());
				$tmpl->set('msg',get_message());
							
				//header("Location: index.php?mensaje=siii");
				echo $tmpl->render();
			}
		}
	}
	else{
		// Tenemos login del usuario
		if(isset($_GET['action'])&& $_GET['action']== 'logout'){
			logout_user();
		}
		else{
			redirect_page('index');
		}
	}
?>
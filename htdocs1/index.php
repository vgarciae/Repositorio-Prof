<?php
/**
* Comentario
*/
require_once('config.php');
require_once(CUR_LIB_PATH.'db.php');
require_once(CUR_LIB_PATH.'helper.php');
require_once(CUR_LIB_PATH.'template.php');

sec_session_start();

//echo "Hola";
//die();

if(login_check($mysqli)){
	class Dashboard{

		protected $users=null;
		protected $conn=null;

		public function Dashboard($mysqli){
			$this->users=array();
			$this->conn=$mysqli;
			$this->getAllUsers();
		}	
		protected function getAllUsers(){
			if($stmt=$this->conn->prepare("SELECT id,username,email,firstname,lastname FROM members")){
				$stmt->execute();
				$result=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
				$this->users=$result;
			}
		}
		
		public function getUsers(){
			return $this->users;
		}
	}

	$app=new Dashboard($mysqli);
	$tmpl=new Template('default');

	$tmpl->set('title',getPageName('Dashboard'));
	$tmpl->set('wpath',getWebPath());
	$tmpl->set('users',$app->getUsers());
	$tmpl->set('user_name',$_SESSION['username']);
	$tmpl->set('user_id',$_SESSION['user_id']);
	
	echo $tmpl->render();
}
else{
	redirect_page('login');
}
?>


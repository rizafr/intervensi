<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";
require_once 'Zend/Session/Namespace.php';

class Home_IndexController extends Zend_Controller_Action {
	
    public function init() {
       // Local to this controller only; affects all actions, as loaded in init:
	   //UNTUK SETTING GLOBAL BASE PATH
		$registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();	   
		$this->view->baseData = $registry->get('baseData');
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');	 
		$this->sso_serv = Sso_User_Service::getInstance();
		$this->penduduk_serv = Data_Penduduk_Service::getInstance();
		$this->menu_serv = Menu_Service::getInstance();

		$ssouserpengguna = new Zend_Session_Namespace('ssouserpengguna');
		$ssouserpassword= new Zend_Session_Namespace('ssouserpassword');
		$ssouserKodeInstansi= new Zend_Session_Namespace('ssouserKodeInstansi');
		$ssouserlevel = new Zend_Session_Namespace('ssouserlevel');
		
		$this->pengguna =$ssouserpengguna->pengguna;			
		$this->password =$ssouserpassword->password;
		$this->KodeInstansi =$ssouserKodeInstansi->KodeInstansi;
		$this->level =$ssouserlevel->level;
    }
	
    public function indexAction() {
		$this->view->p = $_REQUEST['p'];
		$this->view->penggguna = $_REQUEST['u'];
		$this->view->pengguna = $this->sso_serv->getUsername($this->view->penggguna);
		$request = $this->getRequest();   
		$ns = new Zend_Session_Namespace('HelloWorld'); 
		if(!isset($ns->yourLoginRequest)){ 
			$ns->yourLoginRequest = 1; 
		}else{ 
			$ns->yourLoginRequest++; 
		} 
		$this->view->checksess = $ns->yourLoginRequest;
    }
	
	
	public function homeAction(){
		$pengguna = $_POST['pengguna'];
		$password = $_POST['password'];
		
		if ($pengguna && $password) {				
			$hasiluser = $this->sso_serv->getDataUser1($pengguna,$password);
			if($hasiluser){
				$ssouserpengguna = new Zend_Session_Namespace('ssouserpengguna');
				$ssouserpassword= new Zend_Session_Namespace('ssouserpassword');
				$ssouserKodeInstansi = new Zend_Session_Namespace('ssouserKodeInstansi');
				$ssouserlevel = new Zend_Session_Namespace('ssouserlevel');
				
				$pengguna     = $hasiluser->pengguna;	
				$password     = $hasiluser->password;	
				$KodeInstansi     = $hasiluser->KodeInstansi;	
				$level     = $hasiluser->level;
				$instansi     = $hasiluser->Instansi;
				
				$ssouserpengguna->pengguna = $hasiluser->pengguna;	
				$ssouserpassword->password = $hasiluser->password;	
				$ssouserKodeInstansi->KodeInstansi = $hasiluser->KodeInstansi;	
				$ssouserlevel->level = $hasiluser->level;	
				
				$this->view->pengguna = $ssouserpengguna->pengguna;
				$this->view->password = $ssouserpassword->password;
				$this->view->KodeInstansi = $ssouserKodeInstansi->KodeInstansi;
				$this->view->level = $ssouserlevel->level;
				$this->view->Instansi = $instansi;
				
				if (!$pengguna){
					$pengguna =$this->pengguna;
				}
				$this->view;	
				$this->view->menuKomponen = $this->menu_serv->getKomponen();
				$this->view->menuKomponenSub = $this->menu_serv->getKomponenSub();
				$this->view->menuKomponenSubDetail = $this->menu_serv->getKomponenSubDetail();
				$this->view->kelurahan = $this->menu_serv->getKelurahan();
				$this->view->kecamatan = $this->menu_serv->getKecamatan();
				
			}else{
				$this->view->pengguna=$_POST['pengguna'];
				$this->view->password=$_POST['password'];
				$this->view->pesanlogin ="salah";
				$this->view->pesanKesalahan = 'Nama Pengguna atau Kata Sandi Salah atau menu aplikasi ini bukan autorisasi anda';
				$this->indexAction();
				$this->render('index'); 
				$this->view->par=$_POST['par']; 
			}
		} else {
			 $this->view->pesanlogin ="kosong";
			 $this->view->pesan = "User dan Password Kosong";
			 $this->indexAction();
			 $this->render('index'); 
			 $this->view->par=$_POST['par']; 
		} 
	}
	
	public function depanAction() {
		$pengguna =$this->pengguna;			
		$password =$this->password;
		$KodeInstansi =$this->KodeInstansi;
		$Instansi =$this->Instansi;
		$level =$this->level;

		$this->view->KodeInstansi =$KodeInstansi;
		$this->view->level =$level;
		$this->view->pengguna =$pengguna;		
		$this->view->Instansi =$Instansi;		
		
		
		$this->render('home');
    }
	
	//MENAMPILKAN MENU
	public function menuAction(){
		$this->view->menuKomponen = $this->menu_serv->getKomponen();
		$this->view->menuKomponenSub = $this->menu_serv->getKomponenSub();
		$this->view->menuKomponenSubDetail = $this->menu_serv->getKomponenSubDetail();
		$this->view->kelurahan = $this->menu_serv->getKelurahan();
		$this->view->kecamatan = $this->menu_serv->getKecamatan();
	}	
}
?>
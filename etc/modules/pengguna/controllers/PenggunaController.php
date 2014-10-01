<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";

class Pengguna_PenggunaController extends Zend_Controller_Action {
	
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
	
    }
	
	public function homeAction(){
		$this->view;	
		$this->view->menuKomponen = $this->menu_serv->getKomponen();
		$this->view->menuKomponenSub = $this->menu_serv->getKomponenSub();
		$this->view->menuKomponenSubDetail = $this->menu_serv->getKomponenSubDetail();
		$this->view->kelurahan = $this->menu_serv->getKelurahan();
		$this->view->kecamatan = $this->menu_serv->getKecamatan();
	}
	
	//MENAMPILKAN MENU
	public function menuAction(){
		$this->view->menuKomponen = $this->menu_serv->getKomponen();
		$this->view->menuKomponenSub = $this->menu_serv->getKomponenSub();
		$this->view->menuKomponenSubDetail = $this->menu_serv->getKomponenSubDetail();
		$this->view->kelurahan = $this->menu_serv->getKelurahan();
		$this->view->kecamatan = $this->menu_serv->getKecamatan();
	}
	
	//MENU PENGGUNA	
	public function penggunamenujsAction(){
		header('content-type : text/javascript');
		$this->render('penggunamenujs');
    }
	
	public function penggunamenuAction(){
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'p.pengguna';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
							 
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totPengguna = $this->menu_serv->getcaripengguna($dataMasukan,0,0,0);
		$this->view->penggunaMenu = $this->menu_serv->getcaripengguna($dataMasukan,$currentPage, $numToDisplay,$this->view->totPengguna); 		
	}
	
	public function penggunaolahdataAction(){
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$id= $_REQUEST['id'];
		$this->view->penggunaMenu = $this->menu_serv->getpenggunaedit($id);
		$this->view->instansiList = $this->menu_serv->getInstansiListAll();
	}
	
	public function simpanpenggunaAction(){
		$pengguna = trim($_POST['pengguna']);
		$password = trim($_POST['password']);
		$KodeInstansi = $_POST['KodeInstansi'];
		$level = $_POST['level'];
		
		$dataMasukan = array("pengguna" => $pengguna,
							 "password" => $password,
							 "KodeInstansi" => $KodeInstansi,
							 "level" => $level
							 );
									 
		$this->view->penggunaInsert = $this->menu_serv->getsimpanpengguna($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Pengguna";		
		$this->view->hasil = $this->view->penggunaInsert;
		
		$this->penggunamenuAction();
		$this->render('penggunamenu');	
	}
	
	public function simpanpenggunaeditAction(){
		$id = $_POST['id'];
		$pengguna = $_POST['pengguna'];
		$password = $_POST['password'];
		$KodeInstansi = $_POST['KodeInstansi'];
		$level = $_POST['level'];
		
		$dataMasukan = array("id" => $id,
							 "pengguna" => $pengguna,
							 "password" => $password,
							 "KodeInstansi" => $KodeInstansi,
							 "level" => $level
							 );
									 
		$this->view->ubahPengguna = $this->menu_serv->getsimpanpenggunaedit($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Pengguna";
		$this->view->hasil = $this->view->ubahPengguna;
		
		$this->penggunamenuAction();
		$this->render('penggunamenu');	
	}
	
	public function penggunahapusAction(){
		$id= $_REQUEST['id'];
		//echo $user_id;
		$dataMasukan = array("id" => $id);
		
		$this->view->penggunaUpdate = $this->menu_serv->gethapuspengguna($id);
		$this->view->proses = "3";	
		$this->view->keterangan = "Pengguna";
		$this->view->hasil = $this->view->penggunaUpdate;		
		
		$this->penggunamenuAction();
		$this->render('penggunamenu');	
	}
}
?>
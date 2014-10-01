<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";

class Kelurahan_KelurahanController extends Zend_Controller_Action {
	
    public function init() {
       // Local to this controller only; affects all actions, as loaded in init:
	   //UNTUK SETTING GLOBAL BASE PATH
		$registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();	   
		$this->view->baseData = $registry->get('baseData');
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');	 
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
	
	public function retailAction(){
		$this->view; //panggil view retail
		//$this->view->\retail=$_GET['retail'];
	}
	
	public function homeAction(){
		$this->view;
		echo "tes";
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
	
	//MENU KELURAHAN
	public function kelurahanmenujsAction(){
		header('content-type : text/javascript');
		$this->render('kelurahanmenujs');
    }
	
	public function kelurahanmenuAction(){
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari'];
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'kode_kelurahan';
		$sort			= 'asc';
		
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
							 
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKelurahan = $this->menu_serv->getcarikelurahan($dataMasukan,0,0,0);
		$this->view->kelurahanMenu = $this->menu_serv->getcarikelurahan($dataMasukan,$currentPage, $numToDisplay,$this->view->totKelurahan);
	
	
	}
	
	public function kelurahanolahdataAction(){
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$kode_kelurahan= $_REQUEST['kode_kelurahan'];
		$this->view->kelurahanMenu = $this->menu_serv->getkelurahanedit($kode_kelurahan);
	}
	
	public function simpankelurahanAction(){
		$kode_kelurahan = $_POST['kode_kelurahan'];
		$Kelurahan = $_POST['Kelurahan'];
		
		$dataMasukan = array("kode_kelurahan" => $kode_kelurahan,
							 "Kelurahan" => $Kelurahan);
									 
		$this->view->kelurahanInsert = $this->menu_serv->getsimpankelurahan($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Kelurahan";		
		$this->view->hasil = $this->view->kelurahanInsert;
		
		$this->kelurahanmenuAction();
		$this->render('kelurahanmenu');	
	}
		
	public function simpankelurahaneditAction(){
		$kode_kelurahan = $_POST['kode_kelurahan'];
		$Kelurahan = $_POST['Kelurahan'];
		
		$dataMasukan = array("kode_kelurahan" => $kode_kelurahan,
							 "Kelurahan" => $Kelurahan);
									 
		$this->view->ubahKelurahan = $this->menu_serv->getsimpankelurahanedit($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Kelurahan";
		$this->view->hasil = $this->view->ubahKelurahan;
		
		$this->kelurahanmenuAction();
		$this->render('kelurahanmenu');	
	}
	
	public function kelurahanhapusAction(){
		$kode_kelurahan= $_REQUEST['kode_kelurahan'];
		$dataMasukan = array("kode_kelurahan" => $kode_kelurahan);
		
		$this->view->kelurahanUpdate = $this->menu_serv->gethapuskelurahan($kode_kelurahan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Kelurahan";
		$this->view->hasil = $this->view->kelurahanUpdate;			
		
		$this->kelurahanmenuAction();
		$this->render('kelurahanmenu');	
	}
}
?>
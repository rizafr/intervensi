<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";

class Provinsi_ProvinsiController extends Zend_Controller_Action {
	
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
	
	//MENU PROVINSI	
	public function provinsimenujsAction(){
		header('content-type : text/javascript');
		$this->render('provinsimenujs');
    }
	
	public function provinsimenuAction(){
			$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined')){
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'kode_provinsi';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
							 
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totProvinsi = $this->menu_serv->getcariprovinsi($dataMasukan,0,0,0);
		$this->view->provinsiMenu = $this->menu_serv->getcariprovinsi($dataMasukan,$currentPage, $numToDisplay,$this->view->totProvinsi);
	}
	
	public function provinsiolahdataAction(){
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$kode_provinsi= $_REQUEST['kode_provinsi'];
		$this->view->provinsiMenu = $this->menu_serv->getprovinsiedit($kode_provinsi);
	}
	
	public function simpanprovinsiAction(){
		$kode_provinsi = $_POST['kode_provinsi'];
		$provinsi = $_POST['provinsi'];
		
		$dataMasukan = array("kode_provinsi" => $kode_provinsi,
							 "provinsi" => $provinsi);
									 
		$this->view->provinsiInsert = $this->menu_serv->getsimpanprovinsi($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Provinsi";		
		$this->view->hasil = $this->view->provinsiInsert;
		
		$this->provinsimenuAction();
		$this->render('provinsimenu');	
	}
	
	public function simpanprovinsieditAction(){
		$kode_provinsi = $_POST['kode_provinsi'];
		$provinsi = $_POST['provinsi'];
		
		$dataMasukan = array("kode_provinsi" => $kode_provinsi,
							 "provinsi" => $provinsi);
									 
		$this->view->ubahProvinsi = $this->menu_serv->getsimpanprovinsiedit($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Provinsi";
		$this->view->hasil = $this->view->ubahProvinsi;
		
		$this->provinsimenuAction();
		$this->render('provinsimenu');	
	}
	
	public function provinsihapusAction(){
		$kode_provinsi= $_REQUEST['kode_provinsi'];
		//echo $user_id;
		$dataMasukan = array("kode_provinsi" => $kode_provinsi);
		
		$this->view->provinsiUpdate = $this->menu_serv->gethapusprovinsi($kode_provinsi);
		$this->view->proses = "3";	
		$this->view->keterangan = "Provinsi";
		$this->view->hasil = $this->view->provinsiUpdate;		
		
		$this->provinsimenuAction();
		$this->render('provinsimenu');	
	}
}
?>
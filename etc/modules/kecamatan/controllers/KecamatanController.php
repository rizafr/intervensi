<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";

class Kecamatan_KecamatanController extends Zend_Controller_Action {
	
    public function init() {
       // Local to this controller only; affects all actions, as loaded in init:
	   //UNTUK SETTING GLOBAL BASE PATH
		$registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();	   
		$this->view->baseData = $registry->get('baseData');
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');	 
		$this->sso_serv = Sso_User_Service::getInstance();
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
	
	//MENU KECAMATAN
	
	
	public function kecamatanmenujsAction(){
		header('content-type : text/javascript');
		$this->render('kecamatanmenujs');
    }
	
	public function kecamatanmenuAction(){
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'kode_kecamatan';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
							 
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKecamatan = $this->menu_serv->getcarikecamatan($dataMasukan,0,0,0);
		$this->view->kecamatanMenu = $this->menu_serv->getcarikecamatan($dataMasukan,$currentPage, $numToDisplay,$this->view->totKecamatan);
	}
	
	public function kecamatanolahdataAction(){
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$kode_kecamatan= $_REQUEST['kode_kecamatan'];
		$this->view->kecamatanMenu = $this->menu_serv->getkecamatanedit($kode_kecamatan);
	}
	
	public function simpankecamatanAction(){
		$kode_kecamatan = $_POST['kode_kecamatan'];
		$nama_kecamatan = $_POST['nama_kecamatan'];
		
		$dataMasukan = array("kode_kecamatan" => $kode_kecamatan,
							 "nama_kecamatan" => $nama_kecamatan);
									 
		$this->view->kecamatanInsert = $this->menu_serv->getsimpankecamatan($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Kecamatan";		
		$this->view->hasil = $this->view->kecamatanInsert;
		
		$this->kecamatanmenuAction();
		$this->render('kecamatanmenu');	
	}
	
	public function simpankecamataneditAction(){
		$kode_kecamatan = $_POST['kode_kecamatan'];
		$nama_kecamatan = $_POST['nama_kecamatan'];
		
		$dataMasukan = array("kode_kecamatan" => $kode_kecamatan,
							 "nama_kecamatan" => $nama_kecamatan);
									 
		$this->view->ubahKecamatan = $this->menu_serv->getsimpankecamatanedit($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Kecamatan";
		$this->view->hasil = $this->view->ubahKecamatan;
		
		$this->kecamatanmenuAction();
		$this->render('kecamatanmenu');	
	}
	
	public function kecamatanhapusAction(){
		$kode_kecamatan= $_REQUEST['kode_kecamatan'];
		//echo $user_id;
		$dataMasukan = array("kode_kecamatan" => $kode_kecamatan);
		
		$this->view->kecamatanUpdate = $this->menu_serv->gethapuskecamatan($kode_kecamatan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Kecamatan";
		$this->view->hasil = $this->view->kecamatanUpdate;
		
		$this->kecamatanmenuAction();
		$this->render('kecamatanmenu');	
	}
}
?>
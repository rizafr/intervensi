<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";

class KomponenSub_KomponenSubController extends Zend_Controller_Action {
	
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
	
	//MENU KOMPONEN SUB
	public function komponensubmenujsAction(){
		header('content-type : text/javascript');
		$this->render('komponensubmenujs');
    }	
	
	public function detailkomponensubAction(){
		$KodeSubKomponen= $_REQUEST['KodeSubKomponen'];
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined')){
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'KodeSubKomponen';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
							 
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKomponenSubDetail = $this->menu_serv->getdetailkomponensub($KodeSubKomponen, $dataMasukan,0,0,0);
		$this->view->detail = $this->menu_serv->getdetailkomponensub($KodeSubKomponen, $dataMasukan, $currentPage, $numToDisplay,$this->view->totKomponenSubDetail);
    }
	
	public function komponensubmenuAction(){
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'KodeSubKomponen';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
							 
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKomponenSub = $this->menu_serv->getcarikomponensub($dataMasukan,0,0,0);
		$this->view->komponenSubMenu = $this->menu_serv->getcarikomponensub($dataMasukan,$currentPage, $numToDisplay,$this->view->totKomponenSub);
	}
	
	public function komponensubolahdataAction(){
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$KodeSubKomponen= $_REQUEST['KodeSubKomponen'];
		$this->view->menuKomponen = $this->menu_serv->getKomponen();
		$this->view->komponenSubMenu = $this->menu_serv->getkomponensubedit($KodeSubKomponen);
		$this->view->komponenList = $this->menu_serv->getKomponenListAll();
	}
	
	public function simpankomponensubAction(){
		$KodeSubKomponen = $_POST['KodeSubKomponen'];
		$SubKomponen = $_POST['SubKomponen'];
		$KodeKomponen = $_POST['KodeKomponen'];
		
		$dataMasukan = array("KodeSubKomponen" => $KodeSubKomponen,
							 "SubKomponen" => $SubKomponen,
							 "KodeKomponen" => $KodeKomponen);
		
		$this->view->komponenSubInsert = $this->menu_serv->getsimpankomponensub($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "KomponenSub";		
		$this->view->hasil = $this->view->komponenSubInsert;
		
		$this->komponensubmenuAction();
		$this->render('komponensubmenu');	
	}
	
	public function simpankomponensubeditAction(){
		$KodeSubKomponen = $_POST['KodeSubKomponen'];
		$SubKomponen = $_POST['SubKomponen'];
		$KodeKomponen = $_POST['KodeKomponen'];
		
		$dataMasukan = array("KodeSubKomponen" => $KodeSubKomponen,
							 "SubKomponen" => $SubKomponen,
							 "KodeKomponen" => $KodeKomponen);
		
		$this->view->ubahKomponenSub = $this->menu_serv->getsimpankomponensubedit($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "KomponenSub";
		$this->view->hasil = $this->view->ubahKomponenSub;
		
		$this->komponensubmenuAction();
		$this->render('komponensubmenu');
	}
	
	public function komponensubhapusAction(){
		$KodeSubKomponen= $_REQUEST['KodeSubKomponen'];
		//echo $user_id;
		$dataMasukan = array("KodeSubKomponen" => $KodeSubKomponen);
		
		$this->view->komponensubUpdate = $this->menu_serv->gethapuskomponensub($KodeSubKomponen);
		$this->view->proses = "3";	
		$this->view->keterangan = "Komponen Sub";
		$this->view->hasil = $this->view->komponensubUpdate;
		
		$this->komponensubmenuAction();
		$this->render('komponensubmenu');	
	}
}
?>
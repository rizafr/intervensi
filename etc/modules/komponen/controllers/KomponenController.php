<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";

class Komponen_KomponenController extends Zend_Controller_Action {
	
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
	
	//MENU KOMPONEN
	public function komponenmenujsAction(){
		header('content-type : text/javascript');
		$this->render('komponenmenujs');
    }
	
	public function detailkomponenAction(){
		$KodeKomponen= $_REQUEST['KodeKomponen'];
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
		$this->view->KodeKomponen = $KodeKomponen;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKomponenSub = $this->menu_serv->getdetailkomponen($KodeKomponen, $dataMasukan,0,0,0);
		$this->view->detail = $this->menu_serv->getdetailkomponen($KodeKomponen, $dataMasukan, $currentPage, $numToDisplay,$this->view->totKomponenSub);
    }
	
	public function komponenmenuAction(){
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; 
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'KodeKomponen';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
							 
		$numToDisplay = 10;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKomponen = $this->menu_serv->getcarikomponen($dataMasukan,0,0,0);
		$this->view->komponenMenu = $this->menu_serv->getcarikomponen($dataMasukan,$currentPage, $numToDisplay,$this->view->totKomponen);
	}
	
	public function komponenolahdataAction(){
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$KodeKomponen= $_REQUEST['KodeKomponen'];
		$this->view->komponenMenu = $this->menu_serv->getkomponenedit($KodeKomponen);
	}
	
	public function simpankomponenAction(){
		$KodeKomponen = $_POST['KodeKomponen'];
		$Komponen = $_POST['Komponen'];
		$Urut = $_POST['Urut'];
		
		$dataMasukan = array("KodeKomponen" => $KodeKomponen,
							 "Komponen" => $Komponen,
							 "Urut" => $Urut);
									 
		$this->view->komponenInsert = $this->menu_serv->getsimpankomponen($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Komponen";		
		$this->view->hasil = $this->view->komponenInsert;
		
		$this->komponenmenuAction();
		$this->render('komponenmenu');	
	}
	
	public function simpankomponeneditAction(){
		$KodeKomponen = $_POST['KodeKomponen'];
		$Komponen = $_POST['Komponen'];
		$Urut = $_POST['Urut'];
		
		$dataMasukan = array("KodeKomponen" => $KodeKomponen,
							 "Komponen" => $Komponen,
							 "Urut" => $Urut);
									 
		$this->view->ubahKomponen = $this->menu_serv->getsimpankomponenedit($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Komponen";
		$this->view->hasil = $this->view->ubahKomponen;
		
		$this->komponenmenuAction();
		$this->render('komponenmenu');	
	}
	
	public function komponenhapusAction(){
		$KodeKomponen= $_REQUEST['KodeKomponen'];
		//echo $user_id;
		$dataMasukan = array("KodeKomponen" => $KodeKomponen);
		
		$this->view->komponenUpdate = $this->menu_serv->gethapuskomponen($KodeKomponen);
		$this->view->proses = "3";	
		$this->view->keterangan = "Komponen";
		$this->view->hasil = $this->view->komponenUpdate;		
		
		$this->komponenmenuAction();
		$this->render('komponenmenu');	
	}
	
	
}
?>
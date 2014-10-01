<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";


class KomponenSubDetail_KomponenSubDetailController extends Zend_Controller_Action {
	
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
	
	//MENU KOMPONEN SUB DETAIL
	public function komponensubdetailmenujsAction(){
		header('content-type : text/javascript');
		$this->render('komponensubdetailmenujs');
    }	
	
	
	public function komponensubdetailmenuAction(){		
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'KodeDetailSubKomponen';
		$sort			= 'asc';
		
		$dataMasukan = array("KodeDetailSubKomponen" => $KodeDetailSubKomponen,
							 "SubKomponenDetail" => $SubKomponenDetail,
							 "Satuan" => $Satuan,
							 "KodeSubKomponen" => $KodeSubKomponen,
							 "TotalQuality" => $TotalQuality,
							 "IDRTotalCost" => $IDRTotalCost,
							 "USDTotalCost" => $USDTotalCost,
							 "IDBUSDShare" => $IDBUSDShare);
							 
		$numToDisplay = 10;
		$this->view->cari = $katakunciCari.' '.$kategoriCari;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKomponenSubDetail = $this->menu_serv->getcarikomponensubdetail($dataMasukan,0,0,0);
		$this->view->komponenSubDetailMenu = $this->menu_serv->getcarikomponensubdetail($dataMasukan,$currentPage, $numToDisplay,$this->view->totKomponenSubDetail);
	}
	
	public function komponensubdetailolahdataAction(){
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$KodeDetailSubKomponen= $_REQUEST['KodeDetailSubKomponen'];
		$this->view->komponenSubDetailMenu = $this->menu_serv->getkomponensubdetailedit($KodeDetailSubKomponen);
		$this->view->subKomponenList = $this->menu_serv->getSubKomponenListAll();
	}
	
	
	
	public function simpankomponensubdetailAction(){
		$KodeDetailSubKomponen = $_POST['KodeDetailSubKomponen'];
		$SubKomponenDetail = $_POST['SubKomponenDetail'];
		$Satuan = $_POST['Satuan'];
		$KodeSubKomponen = $_POST['KodeSubKomponen'];
		$TotalQuality = $_POST['TotalQuality'];
		$IDRTotalCost = $_POST['IDRTotalCost'];
		$USDTotalCost = $_POST['USDTotalCost'];
		$IDBUSDShare = $_POST['IDBUSDShare'];
		
		$dataMasukan = array("KodeDetailSubKomponen" => $KodeDetailSubKomponen,
							 "SubKomponenDetail" => $SubKomponenDetail,
							 "Satuan" => $Satuan,
							 "KodeSubKomponen" => $KodeSubKomponen,
							 "TotalQuality" => $TotalQuality,
							 "IDRTotalCost" => $IDRTotalCost,
							 "USDTotalCost" => $USDTotalCost,
							 "IDBUSDShare" => $IDBUSDShare);
									 
		$this->view->komponenInsert = $this->menu_serv->getsimpankomponensubdetail($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Komponen";		
		$this->view->hasil = $this->view->komponenInsert;
		
		$this->komponensubdetailmenuAction();
		$this->render('komponensubdetailmenu');	
	}
	
	public function komponensubdetaileditAction(){
		$KodeDetailSubKomponen = $this->_getParam("KodeDetailSubKomponen");
		$this->view->editkomponensubdetail = $this->menu_serv->getkomponensubdetailedit($KodeDetailSubKomponen);
		$this->view->menuKomponenSub = $this->menu_serv->getKomponenSub();	
	}
	
	public function simpankomponensubdetaileditAction(){
		$KodeDetailSubKomponen = $_POST['KodeDetailSubKomponen'];
		$SubKomponenDetail = $_POST['SubKomponenDetail'];
		$Satuan = $_POST['Satuan'];
		$KodeSubKomponen = $_POST['KodeSubKomponen'];
		$TotalQuality = $_POST['TotalQuality'];
		$IDRTotalCost = $_POST['IDRTotalCost'];
		$USDTotalCost = $_POST['USDTotalCost'];
		$IDBUSDShare = $_POST['IDBUSDShare'];
		
		$dataMasukan = array("KodeDetailSubKomponen" => $KodeDetailSubKomponen,
							 "SubKomponenDetail" => $SubKomponenDetail,
							 "Satuan" => $Satuan,
							 "KodeSubKomponen" => $KodeSubKomponen,
							 "TotalQuality" => $TotalQuality,
							 "IDRTotalCost" => $IDRTotalCost,
							 "USDTotalCost" => $USDTotalCost,
							 "IDBUSDShare" => $IDBUSDShare);
									 
		$this->view->ubahKomponen = $this->menu_serv->getsimpankomponensubdetailedit($dataMasukan);
		
		$this->view->proses = "2";	
		$this->view->keterangan = "Komponen Sub Detail";
		$this->view->hasil = $this->view->ubahKomponen;
		
		$this->komponensubdetailmenuAction();
		$this->render('komponensubdetailmenu');	
	}
	
	public function komponensubdetailhapusAction(){
		$KodeDetailSubKomponen= $_REQUEST['KodeDetailSubKomponen'];
		//echo $user_id;
		$dataMasukan = array("KodeDetailSubKomponen" => $KodeDetailSubKomponen);
		
		$this->view->komponenSubDetailDelete = $this->menu_serv->gethapuskomponensubdetail($KodeDetailSubKomponen);
		
		$this->view->proses = "3";	
		$this->view->keterangan = "Komponen Sub Detail";		
		$this->view->hasil = $this->view->komponenSubDetailDelete;
		$this->komponensubdetailmenuAction();
		$this->render('komponensubdetailmenu');	
	}
	
	

	
	
}
?>
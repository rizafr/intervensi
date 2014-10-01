<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/penduduk/Data_Penduduk_Service.php";
require_once "service/menu/Menu_Service.php";
require_once "service/kegiatan/Kegiatan_Service.php";

class Kegiatan_KegiatanController extends Zend_Controller_Action {
	
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
		$this->kegiatan_serv = Kegiatan_Service::getInstance();		
		
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
	
	//MENU KEGIATAN
	public function kegiatanmenujsAction(){
		header('content-type : text/javascript');
		$this->render('kegiatanmenujs');
    }
	
	public function kegiatanmenuAction(){
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= trim($_POST['carii']);
		$sortBy			= 'KodeKegiatan';
		$sort			= 'asc';
		$KodeInstansi	= $this->KodeInstansi;
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
							 
		$numToDisplay = 10;
		$this->view->cari = $katakunciCari;
		$this->view->KodeInstansi = $this->KodeInstansi;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKegiatan = $this->kegiatan_serv->getcarikegiatan($KodeInstansi, $dataMasukan,0,0,0);
		$this->view->kegiatanMenu = $this->kegiatan_serv->getcarikegiatan($KodeInstansi, $dataMasukan,$currentPage, $numToDisplay,$this->view->totKegiatan);
		
	}
	
	public function kegiatanolahdataAction(){
		$this->view->jenisForm = $this->_getParam('jenisForm');
		$this->view->KodeKegiatan= $_REQUEST['KodeKegiatan'];
		$this->view->KodeInstansi = $this->KodeInstansi;
		$this->view->kegiatanMenu = $this->kegiatan_serv->getkegiatanedit($this->view->KodeKegiatan, $this->view->KodeInstansi);
		$this->view->Instansi = $this->kegiatan_serv->getInstansi($this->view->KodeInstansi);
		$this->view->instansiList = $this->kegiatan_serv->getInstansiListAll();
		$this->view->komponenList = $this->kegiatan_serv->getKomponenListAll();
		$this->view->subKomponenList = $this->kegiatan_serv->getSubKomponenListAll();
		$this->view->subKomponenDetailList = $this->kegiatan_serv->getSubKomponenDetailListAll();
		
	}
	
	public function simpankegiatanAction(){
		$KodeKegiatan = $_POST['KodeKegiatan'];
		$NamaKegiatan = $_POST['NamaKegiatan'];
		$JadwalAwal = $_POST['JadwalAwal'];
		$JadwalAkhir = $_POST['JadwalAkhir'];
		$KodeInstansi = $_POST['KodeInstansi'];
		$KodeKomponen = $_POST['KodeKomponen'];
		$KodeSubKomponen = $_POST['KodeSubKomponen'];
		$KodeDetailSubKomponen = $_POST['KodeDetailSubKomponen'];
		$Anggaran = $_POST['Anggaran'];
		
		$dataMasukan = array("KodeKegiatan" => $KodeKegiatan,
							 "NamaKegiatan" => $NamaKegiatan,
							 "JadwalAwal" => $JadwalAwal,
							 "JadwalAkhir" => $JadwalAkhir,
							 "KodeInstansi" => $KodeInstansi,
							 "KodeKomponen" => $KodeKomponen,
							 "KodeSubKomponen" => $KodeSubKomponen,
							 "KodeDetailSubKomponen" => $KodeDetailSubKomponen,
							 "Anggaran" => $Anggaran);
									 
		$this->view->kegInsert = $this->kegiatan_serv->getsimpankegiatan($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Kegiatan";		
		$this->view->hasil = $this->view->kegInsert;
		
		$this->kegiatanmenuAction();
		$this->render('kegiatanmenu');	
	}
	
	public function simpankegiataneditAction(){
		$KodeKegiatan = $_POST['KodeKegiatan'];
		$NamaKegiatan = $_POST['NamaKegiatan'];
		$JadwalAwal = $_POST['JadwalAwal'];
		$JadwalAkhir = $_POST['JadwalAkhir'];
		$KodeInstansi = $_POST['KodeInstansi'];
		$KodeKomponen = $_POST['KodeKomponen'];
		$KodeSubKomponen = $_POST['KodeSubKomponen'];
		$KodeDetailSubKomponen = $_POST['KodeDetailSubKomponen'];
		$Anggaran = $_POST['Anggaran'];
		
		$dataMasukan = array("KodeKegiatan" => $KodeKegiatan,
							 "NamaKegiatan" => $NamaKegiatan,
							 "JadwalAwal" => $JadwalAwal,
							 "JadwalAkhir" => $JadwalAkhir,
							 "KodeInstansi" => $KodeInstansi,
							 "KodeKomponen" => $KodeKomponen,
							 "KodeSubKomponen" => $KodeSubKomponen,
							 "KodeDetailSubKomponen" => $KodeDetailSubKomponen,
							 "Anggaran" => $Anggaran);
									 
		$this->view->ubahKeg = $this->kegiatan_serv->getsimpankegiatanedit($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Kegiatan";
		$this->view->hasil = $this->view->ubahKeg;
		
		$this->kegiatanmenuAction();
		$this->render('kegiatanmenu');	
	}
	
	public function kegiatanhapusAction(){
		$KodeKegiatan= $_REQUEST['KodeKegiatan'];
		$dataMasukan = array("KodeKegiatan" => $KodeKegiatan);
		
		$this->view->kegUpdate = $this->kegiatan_serv->gethapuskegiatan($KodeKegiatan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Kegiatan";
		$this->view->hasil = $this->view->kegUpdate;
		
		$this->kegiatanmenuAction();
		$this->render('kegiatanmenu');	
	}
	
	//=======================================================================================================================
	public function namapendudukAction() {
		$NIK = $this->_getParam("NIK");
	
		$NIK = $_REQUEST["NIK"];
		$dataMasukan = array("NIK"  => $NIK);
		$this->view->NIK = $NIK;
		$this->view->namaPenduduk = $this->kegiatan_serv->getNamaPenduduk2($dataMasukan);
    }
	
	public function penerimamenuAction(){
		$KodeKegiatan = $this->_getParam("KodeKegiatan");
		
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$KodeKegiatan= $_REQUEST['KodeKegiatan'];
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'KodeKegiatan';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);			 
		$numToDisplay = 10;
		$this->view->KodeKegiatan = $KodeKegiatan;
		$this->view->kategoriCari = $kategoriCari;
		$this->view->cari = $katakunciCari;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKegiatan = $this->kegiatan_serv->getpenerima($KodeKegiatan,$dataMasukan,0,0,0);
		$this->view->penerimaMenu = $this->kegiatan_serv->getpenerima($KodeKegiatan, $dataMasukan,$currentPage, $numToDisplay,$this->view->totKegiatan);
		$this->view->penerimaMenu2 = $this->kegiatan_serv->getpenerima2($KodeKegiatan,$currentPage, $numToDisplay,$this->view->totKegiatan);
	}
	
	public function penerimaolahdataAction(){
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$KodeKegiatan= $_REQUEST['KodeKegiatan'];
		$NamaKegiatan = $this->_getParam("NamaKegiatan");		
		$NIK = $_REQUEST["NIK"];
		
		$this->view->kelurahanList = $this->kegiatan_serv->getKelurahanListAll();
		$this->view->KodeKegiatan = $KodeKegiatan;
		$this->view->NamaKegiatan = $NamaKegiatan;	
		$this->view->penerimaOlahMenu = $this->kegiatan_serv->getpenerimaedit($NIK, $KodeKegiatan);
	}
	
	public function simpanpenerimaAction(){
		$IdPendaftaran = $_POST['IdPendaftaran'];
		$NIK = trim($_POST['NIK']);
		$NamaLengkap = trim($_POST['NamaLengkap']);
		$Alamat = trim($_POST['Alamat']);
		$Status=trim($_POST['Status']);
		$Kelurahan = trim($_POST['Kelurahan']);
		$KodeKegiatan = $_POST['KodeKegiatan'];
		$Ket = trim($_POST['Ket']);
		
		$dataMasukan = array("NIK" => $NIK,
							 "IdPendaftaran" => $IdPendaftaran,
							 "NamaLengkap" => $NamaLengkap,
							 "Alamat" => $Alamat,
							 "Kelurahan" => $Kelurahan,
							 "Status" => $Status,
							 "KodeKegiatan" => $KodeKegiatan,
							 "Ket" => $Ket);
		$cekDuplikasi = $this->kegiatan_serv->getCekDuplikasi($NIK, $KodeKegiatan);	
		$jmlResult = count($cekDuplikasi);
		if($jmlResult > 0){
			$this->view->proses = "1";	
			$this->view->keterangan = "Penerima";		
			$this->view->hasil = "Data sudah ada";
		}else{
			$this->view->kegInsert = $this->kegiatan_serv->getsimpanpenerima($dataMasukan);
			$this->view->proses = "1";	
			$this->view->keterangan = "Penerima";		
			$this->view->hasil = $this->view->kegInsert;
		}
		
		$this->penerimamenuAction();
		$this->render('penerimamenu');	
	}
	
	public function simpanpenerimaeditAction(){
		$IdPendaftaran = $_POST['IdPendaftaran'];
		$NIK = $_POST['NIK'];
		$NamaLengkap = trim($_POST['NamaLengkap']);
		$Alamat = trim($_POST['Alamat']);
		$Kelurahan = trim($_POST['Kelurahan']);
		$Status = trim($_POST['Status']);
		$KodeKegiatan = $_POST['KodeKegiatan'];
		$Ket = trim($_POST['Ket']);
		
		$dataMasukan = array("IdPendaftaran" => $IdPendaftaran,
							 "NIK" => $NIK,
							 "NamaLengkap" => $NamaLengkap,
							 "Alamat" => $Alamat,
							 "Kelurahan" => $Kelurahan,
							 "Status" => $Status,
							 "KodeKegiatan" => $KodeKegiatan,
							 "Ket" => $Ket);
									 
		$this->view->ubahKeg = $this->kegiatan_serv->getsimpanpenerimaedit($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Penerima";
		$this->view->hasil = $this->view->ubahKeg;
		
		$this->penerimamenuAction();
		$this->render('penerimamenu');	
	}
	
	public function penerimahapusAction(){
		$NIK = $this->_getParam("NIK");
		$KodeKegiatan = $this->_getParam("KodeKegiatan");
		
		$this->view->hapusKeg = $this->kegiatan_serv->gethapuspenerima($NIK, $KodeKegiatan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Penerima";
		$this->view->hasil = $this->view->hapusKeg;
		
		$this->penerimamenuAction();
		$this->render('penerimamenu');	
	}
}
?>

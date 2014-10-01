<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_AdmsifatSurat_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_SifatSuratController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	   // $ssogroup = new Zend_Session_Namespace('ssogroup');
	   //echo "TEST ".$ssogroup->n_user_grp." ".$ssogroup->i_user." ".$ssogroup->i_peg_nip;
	   $this->user  = 'cdr';
	   
		$this->sifatSurat_serv = Adm_AdmsifatSurat_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroup = new Zend_Session_Namespace('ssogroup');
	    $this->iduser =$ssogroup->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function sifatSuratjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('sifatSuratjs');
    }
	
	//test OPen report
	//----------------------
	public function sifatSuratlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_srtsifat';
		$sortBy			= 'n_srtsifat';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totSifatSuratList = $this->sifatSurat_serv->cariSifatSuratList($dataMasukan,0,0);
		$this->view->sifatSuratList = $this->sifatSurat_serv->cariSifatSuratList($dataMasukan,$currentPage, $numToDisplay);
	}
	
	public function sifatSuratolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$c_srtsifat = $_REQUEST['c_srtsifat'];
		//echo "iuser --->".$iUser;
		$this->view->detailSifatSurat = $this->sifatSurat_serv->detailSifatSuratById($c_srtsifat);
	}
	
	public function sifatSuratAction()
	{
		$c_srtsifat		= $_POST['c_srtsifat'];       
		$n_srtsifat		= $_POST['n_srtsifat'];      
		
		$dataMasukan = array("c_srtsifat"  	=>$c_srtsifat,
							"n_srtsifat"  	=>$n_srtsifat);
		
		$this->view->sifatSuratInsert = $this->sifatSurat_serv->sifatSuratInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data sifat surat", $n_srtsifat." (".$c_srtsifat.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Sifat Surat";
		$this->view->hasil = $this->view->sifatSuratInsert;
		
		$this->sifatSuratlistAction();
		$this->render('sifatSuratlist');
	}
	
	public function sifatSuratupdateAction()
	{
		$c_srtsifat		= $_POST['c_srtsifat'];       
		$n_srtsifat		= $_POST['n_srtsifat'];      
		
		$dataMasukan = array("c_srtsifat"  	=>$c_srtsifat,
							"n_srtsifat"  	=>$n_srtsifat);
		
		$this->view->sifatSuratUpdate = $this->sifatSurat_serv->sifatSuratUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data satuan lampiran", $nm_satuanlampiran." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Sifat Surat";
		$this->view->hasil = $this->view->sifatSuratUpdate;
		
		$this->sifatSuratlistAction();
		$this->render('sifatSuratlist');
	}
	
	public function sifatSurathapusAction()
	{
		$c_srtsifat 		= $_REQUEST['c_srtsifat'];
		
		$dataMasukan = array("c_srtsifat" => $c_srtsifat);
		
		$this->view->sifatSuratUpdate = $this->sifatSurat_serv->sifatSuratHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data satuan lampiran", $nm_satuanlampiran." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Sifat Surat";
		$this->view->hasil = $this->view->sifatSuratUpdate;
		
		$this->sifatSuratlistAction();
		$this->render('sifatSuratlist');
	}
	

}
?>
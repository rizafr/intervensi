<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admstatusmahasiswa_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_StatusmahasiswaController extends Zend_Controller_Action {
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
	   // $ssostatusmahasiswa = new Zend_Session_Namespace('ssostatusmahasiswa');
	   //echo "TEST ".$ssostatusmahasiswa->n_statusmahasiswa_grp." ".$ssostatusmahasiswa->i_statusmahasiswa." ".$ssostatusmahasiswa->i_peg_level_position;
	   $this->statusmahasiswa  = 'cdr';
	   
		$this->statusmahasiswa_serv = Adm_Admstatusmahasiswa_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssostatusmahasiswa = new Zend_Session_Namespace('ssostatusmahasiswa');
	    $this->iduser =$ssostatusmahasiswa->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function statusmahasiswajsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('statusmahasiswajs');
    }
	
	//test OPen report
	//----------------------
	public function statusmahasiswalistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_statusmhs';
		$sortBy			= 'n_statusmhs';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totStatusmahasiswaList = $this->statusmahasiswa_serv->cariStatusmahasiswaList($dataMasukan,0,0);
		$this->view->statusmahasiswaList = $this->statusmahasiswa_serv->cariStatusmahasiswaList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function statusmahasiswaolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iStatusmahasiswa = $_REQUEST['id'];
		
		$this->view->detailStatusmahasiswa = $this->statusmahasiswa_serv->detailStatusmahasiswaById($iStatusmahasiswa);
	}
	
	public function statusmahasiswaAction()
	{
		$id		= $_POST['id'];       
		$n_statusmhs		= $_POST['n_statusmhs'];      
		$c_statusmhs		= $_POST['c_statusmhs'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_statusmhs"  	=>$n_statusmhs,"c_statusmhs"  	=>$c_statusmhs);
			
		$this->view->statusmahasiswaInsert = $this->statusmahasiswa_serv->statusmahasiswaInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data statusmahasiswa", $n_statusmahasiswa." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Statusmahasiswa";
		$this->view->hasil = $this->view->statusmahasiswaInsert;
		
		$this->statusmahasiswalistAction();
		$this->render('statusmahasiswalist');
	}
	
	public function statusmahasiswaupdateAction()
	{
		$id		= $_POST['id'];       
		$n_statusmhs		= $_POST['n_statusmhs'];      
		$c_statusmhs		= $_POST['c_statusmhs'];      
		$i_entry 		= $this->statusmahasiswa;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_statusmhs"  	=>$n_statusmhs,
							"c_statusmhs"  	=>$c_statusmhs);
		
		$this->view->statusmahasiswaUpdate = $this->statusmahasiswa_serv->statusmahasiswaUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data statusmahasiswa", $n_statusmahasiswa." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Statusmahasiswa";
		$this->view->hasil = $this->view->statusmahasiswaUpdate;
		
		$this->statusmahasiswalistAction();
		$this->render('statusmahasiswalist');
	}
	
	public function statusmahasiswahapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->statusmahasiswaUpdate = $this->statusmahasiswa_serv->statusmahasiswaHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data statusmahasiswa", $n_statusmahasiswa." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Statusmahasiswa";
		$this->view->hasil = $this->view->statusmahasiswaUpdate;
		
		$this->statusmahasiswalistAction();
		$this->render('statusmahasiswalist');
	}
	

}
?>
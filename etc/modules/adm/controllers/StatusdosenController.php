<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admstatusdosen_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_StatusdosenController extends Zend_Controller_Action {
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
	   // $ssostatusdosen = new Zend_Session_Namespace('ssostatusdosen');
	   //echo "TEST ".$ssostatusdosen->n_statusdosen_grp." ".$ssostatusdosen->i_statusdosen." ".$ssostatusdosen->i_peg_level_position;
	   $this->statusdosen  = 'cdr';
	   
		$this->statusdosen_serv = Adm_Admstatusdosen_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssostatusdosen = new Zend_Session_Namespace('ssostatusdosen');
	    $this->iduser =$ssostatusdosen->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function statusdosenjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('statusdosenjs');
    }
	
	//test OPen report
	//----------------------
	public function statusdosenlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_statusdsn';
		$sortBy			= 'n_statusdsn';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totStatusdosenList = $this->statusdosen_serv->cariStatusdosenList($dataMasukan,0,0);
		$this->view->statusdosenList = $this->statusdosen_serv->cariStatusdosenList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function statusdosenolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iStatusdosen = $_REQUEST['id'];
		
		$this->view->detailStatusdosen = $this->statusdosen_serv->detailStatusdosenById($iStatusdosen);
	}
	
	public function statusdosenAction()
	{
		$id		= $_POST['id'];       
		$n_statusdsn		= $_POST['n_statusdsn'];      
		$c_statusdsn		= $_POST['c_statusdsn'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_statusdsn"  	=>$n_statusdsn,"c_statusdsn"  	=>$c_statusdsn);
			
		$this->view->statusdosenInsert = $this->statusdosen_serv->statusdosenInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data statusdosen", $n_statusdosen." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Statusdosen";
		$this->view->hasil = $this->view->statusdosenInsert;
		
		$this->statusdosenlistAction();
		$this->render('statusdosenlist');
	}
	
	public function statusdosenupdateAction()
	{
		$id		= $_POST['id'];       
		$n_statusdsn		= $_POST['n_statusdsn'];      
		$c_statusdsn		= $_POST['c_statusdsn'];      
		$i_entry 		= $this->statusdosen;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_statusdsn"  	=>$n_statusdsn,
							"c_statusdsn"  	=>$c_statusdsn);
		
		$this->view->statusdosenUpdate = $this->statusdosen_serv->statusdosenUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data statusdosen", $n_statusdosen." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Statusdosen";
		$this->view->hasil = $this->view->statusdosenUpdate;
		
		$this->statusdosenlistAction();
		$this->render('statusdosenlist');
	}
	
	public function statusdosenhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->statusdosenUpdate = $this->statusdosen_serv->statusdosenHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data statusdosen", $n_statusdosen." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Statusdosen";
		$this->view->hasil = $this->view->statusdosenUpdate;
		
		$this->statusdosenlistAction();
		$this->render('statusdosenlist');
	}
	

}
?>
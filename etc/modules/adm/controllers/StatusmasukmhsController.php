<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admstatusmasukmhs_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_StatusmasukmhsController extends Zend_Controller_Action {
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
	   // $ssostatusmasukmhs = new Zend_Session_Namespace('ssostatusmasukmhs');
	   //echo "TEST ".$ssostatusmasukmhs->n_statusmasukmhs_grp." ".$ssostatusmasukmhs->i_statusmasukmhs." ".$ssostatusmasukmhs->i_peg_level_position;
	   $this->statusmasukmhs  = 'cdr';
	   
		$this->statusmasukmhs_serv = Adm_Admstatusmasukmhs_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssostatusmasukmhs = new Zend_Session_Namespace('ssostatusmasukmhs');
	    $this->iduser =$ssostatusmasukmhs->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function statusmasukmhsjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('statusmasukmhsjs');
    }
	
	//test OPen report
	//----------------------
	public function statusmasukmhslistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_stsmasukmhs';
		$sortBy			= 'n_stsmasukmhs';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totStatusmasukmhsList = $this->statusmasukmhs_serv->cariStatusmasukmhsList($dataMasukan,0,0);
		$this->view->statusmasukmhsList = $this->statusmasukmhs_serv->cariStatusmasukmhsList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function statusmasukmhsolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iStatusmasukmhs = $_REQUEST['id'];
		
		$this->view->detailStatusmasukmhs = $this->statusmasukmhs_serv->detailStatusmasukmhsById($iStatusmasukmhs);
	}
	
	public function statusmasukmhsAction()
	{
		$id		= $_POST['id'];       
		$n_stsmasukmhs		= $_POST['n_stsmasukmhs'];      
		$c_stsmasukmhs		= $_POST['c_stsmasukmhs'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_stsmasukmhs"  	=>$n_stsmasukmhs,"c_stsmasukmhs"  	=>$c_stsmasukmhs);
			
		$this->view->statusmasukmhsInsert = $this->statusmasukmhs_serv->statusmasukmhsInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data statusmasukmhs", $n_statusmasukmhs." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Statusmasukmhs";
		$this->view->hasil = $this->view->statusmasukmhsInsert;
		
		$this->statusmasukmhslistAction();
		$this->render('statusmasukmhslist');
	}
	
	public function statusmasukmhsupdateAction()
	{
		$id		= $_POST['id'];       
		$n_stsmasukmhs		= $_POST['n_stsmasukmhs'];      
		$c_stsmasukmhs		= $_POST['c_stsmasukmhs'];      
		$i_entry 		= $this->statusmasukmhs;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_stsmasukmhs"  	=>$n_stsmasukmhs,
							"c_stsmasukmhs"  	=>$c_stsmasukmhs);
		
		$this->view->statusmasukmhsUpdate = $this->statusmasukmhs_serv->statusmasukmhsUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data statusmasukmhs", $n_statusmasukmhs." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Statusmasukmhs";
		$this->view->hasil = $this->view->statusmasukmhsUpdate;
		
		$this->statusmasukmhslistAction();
		$this->render('statusmasukmhslist');
	}
	
	public function statusmasukmhshapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->statusmasukmhsUpdate = $this->statusmasukmhs_serv->statusmasukmhsHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data statusmasukmhs", $n_statusmasukmhs." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Statusmasukmhs";
		$this->view->hasil = $this->view->statusmasukmhsUpdate;
		
		$this->statusmasukmhslistAction();
		$this->render('statusmasukmhslist');
	}
	

}
?>
<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admthnajaran_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_thnAjaranController extends Zend_Controller_Action {
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
	   // $ssothnajaran = new Zend_Session_Namespace('ssothnajaran');
	   //echo "TEST ".$ssothnajaran->n_thnajaran_grp." ".$ssothnajaran->i_thnajaran." ".$ssothnajaran->i_peg_level_position;
	   $this->thnajaran  = 'cdr';
	   
		$this->thnajaran_serv = Adm_Admthnajaran_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssothnajaran = new Zend_Session_Namespace('ssothnajaran');
	    $this->iduser =$ssothnajaran->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function thnajaranjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('thnajaranjs');
    }
	
	//test OPen report
	//----------------------
	public function thnajaranlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_thnajaran';
		$sortBy			= 'n_thnajaran';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totthnAjaranList = $this->thnajaran_serv->carithnAjaranList($dataMasukan,0,0);
		$this->view->thnajaranList = $this->thnajaran_serv->carithnAjaranList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function thnajaranolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$ithnAjaran = $_REQUEST['id'];
		
		$this->view->detailthnAjaran = $this->thnajaran_serv->detailthnAjaranById($ithnAjaran);
	}
	
	public function thnajaranAction()
	{
		$id		= $_POST['id'];       
		$n_thnajaran		= $_POST['n_thnajaran'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_thnajaran"  	=>$n_thnajaran);
			
		
		
		$this->view->thnajaranInsert = $this->thnajaran_serv->thnajaranInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data thnajaran", $n_thnajaran." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "thnAjaran";
		$this->view->hasil = $this->view->thnajaranInsert;
		
		$this->thnajaranlistAction();
		$this->render('thnajaranlist');
	}
	
	public function thnajaranupdateAction()
	{
		$id		= $_POST['id'];       
		$n_thnajaran		= $_POST['n_thnajaran'];      
		$i_entry 		= $this->thnajaran;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_thnajaran"  	=>$n_thnajaran);
		
		$this->view->thnajaranUpdate = $this->thnajaran_serv->thnajaranUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data thnajaran", $n_thnajaran." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "thnAjaran";
		$this->view->hasil = $this->view->thnajaranUpdate;
		
		$this->thnajaranlistAction();
		$this->render('thnajaranlist');
	}
	
	public function thnajaranhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->thnajaranUpdate = $this->thnajaran_serv->thnajaranHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data thnajaran", $n_thnajaran." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "thnAjaran";
		$this->view->hasil = $this->view->thnajaranUpdate;
		
		$this->thnajaranlistAction();
		$this->render('thnajaranlist');
	}
	

}
?>
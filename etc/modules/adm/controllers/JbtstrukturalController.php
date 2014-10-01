<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admjbtstruktural_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_JbtstrukturalController extends Zend_Controller_Action {
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
	   // $ssojbtstruktural = new Zend_Session_Namespace('ssojbtstruktural');
	   //echo "TEST ".$ssojbtstruktural->n_jbtstruktural_grp." ".$ssojbtstruktural->i_jbtstruktural." ".$ssojbtstruktural->i_peg_level_position;
	   $this->jbtstruktural  = 'cdr';
	   
		$this->jbtstruktural_serv = Adm_Admjbtstruktural_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssojbtstruktural = new Zend_Session_Namespace('ssojbtstruktural');
	    $this->iduser =$ssojbtstruktural->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function jbtstrukturaljsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('jbtstrukturaljs');
    }
	
	//test OPen report
	//----------------------
	public function jbtstrukturallistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_jbtstruktural';
		$sortBy			= 'n_jbtstruktural';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totJbtstrukturalList = $this->jbtstruktural_serv->cariJbtstrukturalList($dataMasukan,0,0);
		$this->view->jbtstrukturalList = $this->jbtstruktural_serv->cariJbtstrukturalList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function jbtstrukturalolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iJbtstruktural = $_REQUEST['id'];
		
		$this->view->detailJbtstruktural = $this->jbtstruktural_serv->detailJbtstrukturalById($iJbtstruktural);
	}
	
	public function jbtstrukturalAction()
	{
		$id		= $_POST['id'];       
		$n_jbtstruktural		= $_POST['n_jbtstruktural'];      
		$c_jbtstruktural		= $_POST['c_jbtstruktural'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_jbtstruktural"  	=>$n_jbtstruktural,"c_jbtstruktural"  	=>$c_jbtstruktural);
			
		$this->view->jbtstrukturalInsert = $this->jbtstruktural_serv->jbtstrukturalInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data jbtstruktural", $n_jbtstruktural." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Jbtstruktural";
		$this->view->hasil = $this->view->jbtstrukturalInsert;
		
		$this->jbtstrukturallistAction();
		$this->render('jbtstrukturallist');
	}
	
	public function jbtstrukturalupdateAction()
	{
		$id		= $_POST['id'];       
		$n_jbtstruktural		= $_POST['n_jbtstruktural'];      
		$c_jbtstruktural		= $_POST['c_jbtstruktural'];      
		$i_entry 		= $this->jbtstruktural;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_jbtstruktural"  	=>$n_jbtstruktural,
							"c_jbtstruktural"  	=>$c_jbtstruktural);
		
		$this->view->jbtstrukturalUpdate = $this->jbtstruktural_serv->jbtstrukturalUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data jbtstruktural", $n_jbtstruktural." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Jbtstruktural";
		$this->view->hasil = $this->view->jbtstrukturalUpdate;
		
		$this->jbtstrukturallistAction();
		$this->render('jbtstrukturallist');
	}
	
	public function jbtstrukturalhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->jbtstrukturalUpdate = $this->jbtstruktural_serv->jbtstrukturalHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data jbtstruktural", $n_jbtstruktural." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Jbtstruktural";
		$this->view->hasil = $this->view->jbtstrukturalUpdate;
		
		$this->jbtstrukturallistAction();
		$this->render('jbtstrukturallist');
	}
	

}
?>
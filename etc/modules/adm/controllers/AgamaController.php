<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admagama_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_AgamaController extends Zend_Controller_Action {
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
	   // $ssoagama = new Zend_Session_Namespace('ssoagama');
	   //echo "TEST ".$ssoagama->n_agama_grp." ".$ssoagama->i_agama." ".$ssoagama->i_peg_level_position;
	   $this->agama  = 'cdr';
	   
		$this->agama_serv = Adm_Admagama_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssoagama = new Zend_Session_Namespace('ssoagama');
	    $this->iduser =$ssoagama->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function agamajsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('agamajs');
    }
	
	//test OPen report
	//----------------------
	public function agamalistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_agama';
		$sortBy			= 'n_agama';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totAgamaList = $this->agama_serv->cariAgamaList($dataMasukan,0,0);
		$this->view->agamaList = $this->agama_serv->cariAgamaList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function agamaolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iAgama = $_REQUEST['id'];
		
		$this->view->detailAgama = $this->agama_serv->detailAgamaById($iAgama);
	}
	
	public function agamaAction()
	{
		$id		= $_POST['id'];       
		$n_agama		= $_POST['n_agama'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_agama"  	=>$n_agama);
			
		
		
		$this->view->agamaInsert = $this->agama_serv->agamaInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data agama", $n_agama." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Agama";
		$this->view->hasil = $this->view->agamaInsert;
		
		$this->agamalistAction();
		$this->render('agamalist');
	}
	
	public function agamaupdateAction()
	{
		$id		= $_POST['id'];       
		$n_agama		= $_POST['n_agama'];      
		$i_entry 		= $this->agama;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_agama"  	=>$n_agama);
		
		$this->view->agamaUpdate = $this->agama_serv->agamaUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data agama", $n_agama." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Agama";
		$this->view->hasil = $this->view->agamaUpdate;
		
		$this->agamalistAction();
		$this->render('agamalist');
	}
	
	public function agamahapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->agamaUpdate = $this->agama_serv->agamaHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data agama", $n_agama." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Agama";
		$this->view->hasil = $this->view->agamaUpdate;
		
		$this->agamalistAction();
		$this->render('agamalist');
	}
	

}
?>
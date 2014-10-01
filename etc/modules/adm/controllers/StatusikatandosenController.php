<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admstatusikatandosen_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_StatusikatandosenController extends Zend_Controller_Action {
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
	   // $ssostatusikatandosen = new Zend_Session_Namespace('ssostatusikatandosen');
	   //echo "TEST ".$ssostatusikatandosen->n_statusikatandosen_grp." ".$ssostatusikatandosen->i_statusikatandosen." ".$ssostatusikatandosen->i_peg_level_position;
	   $this->statusikatandosen  = 'cdr';
	   
		$this->statusikatandosen_serv = Adm_Admstatusikatandosen_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssostatusikatandosen = new Zend_Session_Namespace('ssostatusikatandosen');
	    $this->iduser =$ssostatusikatandosen->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function statusikatandosenjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('statusikatandosenjs');
    }
	
	//test OPen report
	//----------------------
	public function statusikatandosenlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_statusikatandosen';
		$sortBy			= 'n_statusikatandosen';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totStatusikatandosenList = $this->statusikatandosen_serv->cariStatusikatandosenList($dataMasukan,0,0);
		$this->view->statusikatandosenList = $this->statusikatandosen_serv->cariStatusikatandosenList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function statusikatandosenolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iStatusikatandosen = $_REQUEST['id'];
		
		$this->view->detailStatusikatandosen = $this->statusikatandosen_serv->detailStatusikatandosenById($iStatusikatandosen);
	}
	
	public function statusikatandosenAction()
	{
		$id		= $_POST['id'];       
		$n_statusikatandosen		= $_POST['n_statusikatandosen'];      
		$c_statusikatandosen		= $_POST['c_statusikatandosen'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_statusikatandosen"  	=>$n_statusikatandosen,"c_statusikatandosen"  	=>$c_statusikatandosen);
			
		$this->view->statusikatandosenInsert = $this->statusikatandosen_serv->statusikatandosenInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data statusikatandosen", $n_statusikatandosen." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Statusikatandosen";
		$this->view->hasil = $this->view->statusikatandosenInsert;
		
		$this->statusikatandosenlistAction();
		$this->render('statusikatandosenlist');
	}
	
	public function statusikatandosenupdateAction()
	{
		$id		= $_POST['id'];       
		$n_statusikatandosen		= $_POST['n_statusikatandosen'];      
		$c_statusikatandosen		= $_POST['c_statusikatandosen'];      
		$i_entry 		= $this->statusikatandosen;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_statusikatandosen"  	=>$n_statusikatandosen,
							"c_statusikatandosen"  	=>$c_statusikatandosen);
		
		$this->view->statusikatandosenUpdate = $this->statusikatandosen_serv->statusikatandosenUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data statusikatandosen", $n_statusikatandosen." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Statusikatandosen";
		$this->view->hasil = $this->view->statusikatandosenUpdate;
		
		$this->statusikatandosenlistAction();
		$this->render('statusikatandosenlist');
	}
	
	public function statusikatandosenhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->statusikatandosenUpdate = $this->statusikatandosen_serv->statusikatandosenHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data statusikatandosen", $n_statusikatandosen." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Statusikatandosen";
		$this->view->hasil = $this->view->statusikatandosenUpdate;
		
		$this->statusikatandosenlistAction();
		$this->render('statusikatandosenlist');
	}
	

}
?>
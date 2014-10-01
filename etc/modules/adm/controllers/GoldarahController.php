<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admgoldarah_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_GoldarahController extends Zend_Controller_Action {
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
	   // $ssogoldarah = new Zend_Session_Namespace('ssogoldarah');
	   //echo "TEST ".$ssogoldarah->n_goldarah_grp." ".$ssogoldarah->i_goldarah." ".$ssogoldarah->i_peg_level_position;
	   $this->goldarah  = 'cdr';
	   
		$this->goldarah_serv = Adm_Admgoldarah_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogoldarah = new Zend_Session_Namespace('ssogoldarah');
	    $this->iduser =$ssogoldarah->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function goldarahjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('goldarahjs');
    }
	
	//test OPen report
	//----------------------
	public function goldarahlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_goldarah';
		$sortBy			= 'n_goldarah';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totGoldarahList = $this->goldarah_serv->cariGoldarahList($dataMasukan,0,0);
		$this->view->goldarahList = $this->goldarah_serv->cariGoldarahList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function goldaraholahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iGoldarah = $_REQUEST['id'];
		
		$this->view->detailGoldarah = $this->goldarah_serv->detailGoldarahById($iGoldarah);
	}
	
	public function goldarahAction()
	{
		$id		= $_POST['id'];       
		$n_goldarah		= $_POST['n_goldarah'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_goldarah"  	=>$n_goldarah);
			
		
		
		$this->view->goldarahInsert = $this->goldarah_serv->goldarahInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data goldarah", $n_goldarah." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Goldarah";
		$this->view->hasil = $this->view->goldarahInsert;
		
		$this->goldarahlistAction();
		$this->render('goldarahlist');
	}
	
	public function goldarahupdateAction()
	{
		$id		= $_POST['id'];       
		$n_goldarah		= $_POST['n_goldarah'];      
		$i_entry 		= $this->goldarah;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_goldarah"  	=>$n_goldarah);
		
		$this->view->goldarahUpdate = $this->goldarah_serv->goldarahUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data goldarah", $n_goldarah." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Goldarah";
		$this->view->hasil = $this->view->goldarahUpdate;
		
		$this->goldarahlistAction();
		$this->render('goldarahlist');
	}
	
	public function goldarahhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->goldarahUpdate = $this->goldarah_serv->goldarahHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data goldarah", $n_goldarah." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Goldarah";
		$this->view->hasil = $this->view->goldarahUpdate;
		
		$this->goldarahlistAction();
		$this->render('goldarahlist');
	}
	

}
?>
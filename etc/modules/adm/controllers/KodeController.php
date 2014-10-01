<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admkode_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_KodeController extends Zend_Controller_Action {
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
	   // $ssokode = new Zend_Session_Namespace('ssokode');
	   //echo "TEST ".$ssokode->n_kode_grp." ".$ssokode->i_kode." ".$ssokode->i_peg_level_position;
	   $this->kode  = 'cdr';
	   
		$this->kode_serv = Adm_Admkode_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssokode = new Zend_Session_Namespace('ssokode');
	    $this->iduser =$ssokode->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function kodejsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('kodejs');
    }
	
	//test OPen report
	//----------------------
	public function kodelistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_kode';
		$sortBy			= 'n_kode';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKodeList = $this->kode_serv->cariKodeList($dataMasukan,0,0);
		$this->view->kodeList = $this->kode_serv->cariKodeList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function kodeolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iKode = $_REQUEST['id'];
		
		$this->view->detailKode = $this->kode_serv->detailKodeById($iKode);
	}
	
	public function kodeAction()
	{
		$id		= $_POST['id'];       
		$n_kode		= $_POST['n_kode'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_kode"  	=>$n_kode);
			
		
		
		$this->view->kodeInsert = $this->kode_serv->kodeInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data kode", $n_kode." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Kode";
		$this->view->hasil = $this->view->kodeInsert;
		
		$this->kodelistAction();
		$this->render('kodelist');
	}
	
	public function kodeupdateAction()
	{
		$id		= $_POST['id'];       
		$n_kode		= $_POST['n_kode'];      
		$i_entry 		= $this->kode;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_kode"  	=>$n_kode);
		
		$this->view->kodeUpdate = $this->kode_serv->kodeUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data kode", $n_kode." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Kode";
		$this->view->hasil = $this->view->kodeUpdate;
		
		$this->kodelistAction();
		$this->render('kodelist');
	}
	
	public function kodehapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->kodeUpdate = $this->kode_serv->kodeHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data kode", $n_kode." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Kode";
		$this->view->hasil = $this->view->kodeUpdate;
		
		$this->kodelistAction();
		$this->render('kodelist');
	}
	

}
?>
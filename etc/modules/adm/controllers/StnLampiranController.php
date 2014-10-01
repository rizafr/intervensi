<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_AdmstnLampiran_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_StnLampiranController extends Zend_Controller_Action {
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
	   
		
		$this->stnLampiran_serv = Adm_AdmstnLampiran_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroup = new Zend_Session_Namespace('ssogroup');
	    $this->iduser =$ssogroup->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function stnLampiranjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('stnLampiranjs');
    }
	
	//test OPen report
	//----------------------
	public function stnLampiranlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'nm_satuanlampiran';
		$sortBy			= 'nm_satuanlampiran';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totStnLampiranList = $this->stnLampiran_serv->cariStnLampiranList($dataMasukan,0,0);
		$this->view->stnLampiranList = $this->stnLampiran_serv->cariStnLampiranList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function stnLampiranolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$id = $_REQUEST['id'];
		//echo "iuser --->".$iUser;
		$this->view->detailStnLampiran = $this->stnLampiran_serv->detailStnLampiranById($id);
	}
	
	public function stnLampiranAction()
	{
		$id		= $_POST['id'];       
		$nm_satuanlampiran		= $_POST['nm_satuanlampiran'];      
		
		$dataMasukan = array("id"  	=>$id,
							"nm_satuanlampiran"  	=>$nm_satuanlampiran);
		
		$this->view->stnLampiranInsert = $this->stnLampiran_serv->stnLampiranInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data satuan lampiran", $nm_satuanlampiran." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Satuan Lampiran";
		$this->view->hasil = $this->view->stnLampiranInsert;
		
		$this->stnLampiranlistAction();
		$this->render('stnLampiranlist');
	}
	
	public function stnLampiranupdateAction()
	{
		$id		= $_POST['id'];       
		$nm_satuanlampiran		= $_POST['nm_satuanlampiran'];      
		
		$dataMasukan = array("id"  	=>$id,
							"nm_satuanlampiran"  	=>$nm_satuanlampiran);
		
		$this->view->stnLampiranUpdate = $this->stnLampiran_serv->stnLampiranUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data satuan lampiran", $nm_satuanlampiran." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Satuan Lampiran";
		$this->view->hasil = $this->view->stnLampiranUpdate;
		
		$this->stnLampiranlistAction();
		$this->render('stnLampiranlist');
	}
	
	public function stnLampiranhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->stnLampiranUpdate = $this->stnLampiran_serv->stnLampiranHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data satuan lampiran", $nm_satuanlampiran." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Satuan Lampiran";
		$this->view->hasil = $this->view->stnLampiranUpdate;
		
		$this->stnLampiranlistAction();
		$this->render('stnLampiranlist');
	}
	

}
?>
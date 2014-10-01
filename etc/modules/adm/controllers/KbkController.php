<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admkbk_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_KbkController extends Zend_Controller_Action {
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
	   // $ssokbk = new Zend_Session_Namespace('ssokbk');
	   //echo "TEST ".$ssokbk->n_kbk_grp." ".$ssokbk->i_kbk." ".$ssokbk->i_peg_level_position;
	   $this->kbk  = 'cdr';
	   
		$this->kbk_serv = Adm_Admkbk_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssokbk = new Zend_Session_Namespace('ssokbk');
	    $this->iduser =$ssokbk->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function kbkjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('kbkjs');
    }
	
	//test OPen report
	//----------------------
	public function kbklistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_kbk';
		$sortBy			= 'n_kbk';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKbkList = $this->kbk_serv->cariKbkList($dataMasukan,0,0);
		$this->view->kbkList = $this->kbk_serv->cariKbkList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function kbkolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iKbk = $_REQUEST['id'];
		
		$this->view->detailKbk = $this->kbk_serv->detailKbkById($iKbk);
	}
	
	public function kbkAction()
	{
		$id		= $_POST['id'];       
		$n_kbk		= $_POST['n_kbk'];      
		$c_kbk		= $_POST['e_ket'];      
		$c_jur 		= $_POST['c_jur'];     

		$dataMasukan = array("n_kbk"  	=>$n_kbk,
							 "e_ket"  	=>$e_ket,
							 "c_jur"  	=>$c_jur
							 );
		//var_dump($dataMasukan);
		$this->view->kbkInsert = $this->kbk_serv->kbkInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data kbk", $n_kbk." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Kbk";
		$this->view->hasil = $this->view->kbkInsert;
		$this->kbklistAction();
		$this->render('kbklist');
	}
	
	public function kbkupdateAction()
	{//echo "kbkupdateAction------->";
		$id		= $_POST['id'];       
		$n_kbk		= $_POST['n_kbk'];      
		$c_kbk		= $_POST['e_ket'];      
		$c_jur 		= $_POST['c_jur']; 
		
		
		$dataMasukan = array("n_kbk"  	=>$n_kbk,
							 "e_ket"  	=>$e_ket,
							 "c_jur"  	=>$c_jur
							 );
		
		$this->view->kbkUpdate = $this->kbk_serv->kbkUpdate($dataMasukan);
		//var_dump($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data kbk", $n_kbk." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Kbk";
		$this->view->hasil = $this->view->kbkUpdate;
		
		$this->kbklistAction();
		$this->render('kbklist');
	}
	
	public function kbkhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->kbkUpdate = $this->kbk_serv->kbkHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data kbk", $n_kbk." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Kbk";
		$this->view->hasil = $this->view->kbkUpdate;
		
		$this->kbklistAction();
		$this->render('kbklist');
	}
	

}
?>
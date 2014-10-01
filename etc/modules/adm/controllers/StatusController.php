<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admstatus_Service.php";

class Adm_StatusController extends Zend_Controller_Action {
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
	   // $this->username  = 'Yuliah';
	   //$this->nip  = strtoupper($this->id['nip']);
	   // $this->usernip  = '060046350';
	   // $this->kdorg = 'SK1201';
	 // $this->modul    = '5';
	 // $this->category = 'A';
		
		$this->status_serv = Adm_Admstatus_Service::getInstance();
		
		// $this->sdm_caripeg_serv = Sdm_Caripegawai_Service::getInstance();
    }
	
    public function indexAction() {
	   
    }
	
	public function statusjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('statusjs');
    }
	
	//test OPen report
	//----------------------
	public function statuslistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'nm_status';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'nm_status';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totStatusList = $this->status_serv->cariStatusList($dataMasukan,0,0);
		$this->view->statusList = $this->status_serv->cariStatusList($dataMasukan,$currentPage, $numToDisplay);
	}
	
	public function statusolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$kd_status = $_REQUEST['kd_status'];
		//echo "iuser --->".$iUser;
		$this->view->detailStatus = $this->status_serv->detailStatusById($kd_status);
	}
	
	public function statusAction()
	{
		$kd_status		= $_POST['kd_status'];       
		$nm_status		= $_POST['nm_status'];      
		   

		$dataMasukan = array("nm_status"				=>$nm_status);
		
		$this->view->statusInsert = $this->status_serv->statusInsert($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Status";
		$this->view->hasil = $hasil;
		
		$this->statuslistAction();
		$this->render('statuslist');
	}
	
	public function statusupdateAction()
	{
		$kd_status		= $_POST['kd_status'];       
		$nm_status		= $_POST['nm_status'];      
		//$pangkat			= $_POST['pangkat'];      
		$c_statusdelete		= $_POST['c_statusdelete'];           
		$i_entry			= $_POST['i_entry'];          
		$d_entry			= $_POST['d_entry'];   
		
		$dataMasukan = array("kd_status"  	=>$kd_status,
							"nm_status"	 	=>$nm_status,
						//	"pangkat"		 	=>$pangkat,
							"c_statusdelete" 	=>$c_statusdelete,
							"i_entry"  			=>$i_entry,
							"d_entry"			=>$d_entry);
		
		$this->view->statusUpdate = $this->status_serv->statusUpdate($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Status";
		$this->view->hasil = $hasil;
		
		$this->statuslistAction();
		$this->render('statuslist');
	}
	
	public function statushapusAction()
	{
		$kd_status 		= $_REQUEST['kd_status'];
		
		$dataMasukan = array("kd_status" => $kd_status);
		
		$this->view->statusUpdate = $this->status_serv->statusHapus($dataMasukan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Status";
		$this->view->hasil = $hasil;
		
		$this->statuslistAction();
		$this->render('statuslist');
	}
	

}
?>
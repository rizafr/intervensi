<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admjeniskelamin_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_JeniskelaminController extends Zend_Controller_Action {
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
	   // $ssojeniskelamin = new Zend_Session_Namespace('ssojeniskelamin');
	   //echo "TEST ".$ssojeniskelamin->n_jeniskelamin_grp." ".$ssojeniskelamin->i_jeniskelamin." ".$ssojeniskelamin->i_peg_level_position;
	   $this->jeniskelamin  = 'cdr';
	   
		$this->jeniskelamin_serv = Adm_Admjeniskelamin_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssojeniskelamin = new Zend_Session_Namespace('ssojeniskelamin');
	    $this->iduser =$ssojeniskelamin->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function jeniskelaminjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('jeniskelaminjs');
    }
	
	//test OPen report
	//----------------------
	public function jeniskelaminlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_jnskelamin';
		$sortBy			= 'n_jnskelamin';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totJeniskelaminList = $this->jeniskelamin_serv->cariJeniskelaminList($dataMasukan,0,0);
		$this->view->jeniskelaminList = $this->jeniskelamin_serv->cariJeniskelaminList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function jeniskelaminolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iJeniskelamin = $_REQUEST['id'];
		
		$this->view->detailJeniskelamin = $this->jeniskelamin_serv->detailJeniskelaminById($iJeniskelamin);
	}
	
	public function jeniskelaminAction()
	{
		$id		= $_POST['id'];       
		$n_jnskelamin		= $_POST['n_jnskelamin'];      
		$c_jnskelamin		= $_POST['c_jnskelamin'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_jnskelamin"  	=>$n_jnskelamin,"c_jnskelamin"  	=>$c_jnskelamin);
			
		$this->view->jeniskelaminInsert = $this->jeniskelamin_serv->jeniskelaminInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data jeniskelamin", $n_jeniskelamin." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Jeniskelamin";
		$this->view->hasil = $this->view->jeniskelaminInsert;
		
		$this->jeniskelaminlistAction();
		$this->render('jeniskelaminlist');
	}
	
	public function jeniskelaminupdateAction()
	{
		$id		= $_POST['id'];       
		$n_jnskelamin		= $_POST['n_jnskelamin'];      
		$c_jnskelamin		= $_POST['c_jnskelamin'];      
		$i_entry 		= $this->jeniskelamin;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_jnskelamin"  	=>$n_jnskelamin,
							"c_jnskelamin"  	=>$c_jnskelamin);
		
		$this->view->jeniskelaminUpdate = $this->jeniskelamin_serv->jeniskelaminUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data jeniskelamin", $n_jeniskelamin." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Jeniskelamin";
		$this->view->hasil = $this->view->jeniskelaminUpdate;
		
		$this->jeniskelaminlistAction();
		$this->render('jeniskelaminlist');
	}
	
	public function jeniskelaminhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->jeniskelaminUpdate = $this->jeniskelamin_serv->jeniskelaminHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data jeniskelamin", $n_jeniskelamin." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Jeniskelamin";
		$this->view->hasil = $this->view->jeniskelaminUpdate;
		
		$this->jeniskelaminlistAction();
		$this->render('jeniskelaminlist');
	}
	

}
?>
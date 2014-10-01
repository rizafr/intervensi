<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_AdmjenisAjuan_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_JenisAjuanController extends Zend_Controller_Action {
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
	   
		
		$this->jenisAjuan_serv = Adm_AdmjenisAjuan_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroup = new Zend_Session_Namespace('ssogroup');
	    $this->iduser =$ssogroup->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function jenisAjuanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('jenisAjuanjs');
    }
	
	//test OPen report
	//----------------------
	public function jenisAjuanlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_srtajuan';
		$sortBy			= 'n_srtajuan';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totJenisAjuanList = $this->jenisAjuan_serv->cariJenisAjuanList($dataMasukan,0,0);
		$this->view->jenisAjuanList = $this->jenisAjuan_serv->cariJenisAjuanList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function jenisAjuanolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$c_srtajuan = $_REQUEST['c_srtajuan'];
		//echo "iuser --->".$iUser;
		$this->view->detailJenisAjuan = $this->jenisAjuan_serv->detailJenisAjuanById($c_srtajuan);
	}
	
	public function jenisAjuanAction()
	{
		$c_srtajuan		= $_POST['c_srtajuan'];       
		$n_srtajuan		= $_POST['n_srtajuan'];      
		
		$dataMasukan = array("c_srtajuan"  	=>$c_srtajuan,
							"n_srtajuan"  	=>$n_srtajuan);
		
		$this->view->jenisAjuanInsert = $this->jenisAjuan_serv->jenisAjuanInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data surat ajuan", $n_srtajuan." (".$c_srtajuan.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Jenis Ajuan";
		$this->view->hasil = $this->view->jenisAjuanInsert;
		
		$this->jenisAjuanlistAction();
		$this->render('jenisAjuanlist');
	}
	
	public function jenisAjuanupdateAction()
	{
		$c_srtajuan		= $_POST['c_srtajuan'];       
		$n_srtajuan		= $_POST['n_srtajuan'];      
		
		$dataMasukan = array("c_srtajuan"  	=>$c_srtajuan,
							"n_srtajuan"  	=>$n_srtajuan);
		
		$this->view->jenisAjuanUpdate = $this->jenisAjuan_serv->jenisAjuanUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data surat ajuan", $n_srtajuan." (".$c_srtajuan.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Jenis Ajuan";
		$this->view->hasil = $this->view->jenisAjuanUpdate;
		
		$this->jenisAjuanlistAction();
		$this->render('jenisAjuanlist');
	}
	
	public function jenisAjuanhapusAction()
	{
		$c_srtajuan 		= $_REQUEST['c_srtajuan'];
		
		$dataMasukan = array("c_srtajuan" => $c_srtajuan);
		
		$this->view->jenisAjuanUpdate = $this->jenisAjuan_serv->jenisAjuanHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data surat ajuan", $n_srtajuan." (".$c_srtajuan.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Jenis Ajuan";
		$this->view->hasil = $this->view->jenisAjuanUpdate;
		
		$this->jenisAjuanlistAction();
		$this->render('jenisAjuanlist');
	}
	

}
?>
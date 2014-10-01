<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admdosen_Service.php";
require_once "service/adm/Adm_AdmtkPendidikan_Service.php";
require_once "service/adm/Adm_AdmjbtAkademik_Service.php";
require_once "service/adm/Adm_Admstatusikatandosen_Service.php";
require_once "service/adm/Adm_Admagama_Service.php";
require_once "service/adm/Adm_Admjeniskelamin_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_DosenController extends Zend_Controller_Action {
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
	   // $ssodosen = new Zend_Session_Namespace('ssodosen');
	   //echo "TEST ".$ssodosen->n_dosen_grp." ".$ssodosen->i_dosen." ".$ssodosen->i_peg_level_position;
	   $this->dosen  = 'cdr';
	   
		$this->dosen_serv = Adm_Admdosen_Service::getInstance();
		$this->tkPendidikan_serv = Adm_AdmtkPendidikan_Service::getInstance();
		$this->jbtAkademik_serv = Adm_AdmjbtAkademik_Service::getInstance();
		$this->statusikatandosen_serv = Adm_Admstatusikatandosen_Service::getInstance();
		$this->agama_serv = Adm_Admagama_Service::getInstance();
		$this->jeniskelamin_serv = Adm_Admjeniskelamin_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssodosen = new Zend_Session_Namespace('ssodosen');
	    $this->iduser =$ssodosen->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function dosenjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('dosenjs');
    }
	
	//test OPen report
	//----------------------
	public function dosenlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_nama';
		$sortBy			= 'n_nama';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totDosenList = $this->dosen_serv->cariDosenList($dataMasukan,0,0);
		$this->view->dosenList = $this->dosen_serv->cariDosenList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function dosenolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iDosen = $_REQUEST['id'];
		
		$this->view->detailDosen = $this->dosen_serv->detailDosenById($iDosen);
		$this->view->tkPendidikanList = $this->tkPendidikan_serv->getTkPendidikanListAll();
		$this->view->jbtAkademikList = $this->jbtAkademik_serv->getJbtAkademikListAll();
		//var_dump("sita--m-->".$this->view->tkPendidikanList);
		$this->view->statusikatandosenList = $this->statusikatandosen_serv->getStatusikatandosenListAll();
		$this->view->jeniskelaminList = $this->jeniskelamin_serv->getJeniskelaminListAll();
		$this->view->agamaList = $this->agama_serv->getAgamaListAll();
		$this->view->ptList = $this->statusikatandosen_serv->getPtListAll();
	}
	
	public function dosenAction()
	{
		$id		= $_POST['id'];       
		$n_dosen		= $_POST['n_dosen'];      
		$c_dosen		= $_POST['c_dosen'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_dosen"  	=>$n_dosen,
							 "c_dosen"  	=>$c_dosen);
		//var_dump($dataMasukan);
		$this->view->dosenInsert = $this->dosen_serv->dosenInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data dosen", $n_dosen." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Dosen";
		$this->view->hasil = $this->view->dosenInsert;
		$this->dosenlistAction();
		$this->render('dosenlist');
	}
	
	public function dosenupdateAction()
	{
		$id		= $_POST['id'];       
		$n_dosen		= $_POST['n_dosen'];      
		$c_dosen		= $_POST['c_dosen'];      
		$i_entry 		= $this->dosen;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_dosen"  	=>$n_dosen,
							 "c_dosen"  	=>$c_dosen);
		
		$this->view->dosenUpdate = $this->dosen_serv->dosenUpdate($dataMasukan);
		//var_dump($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data dosen", $n_dosen." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Dosen";
		$this->view->hasil = $this->view->dosenUpdate;
		
		$this->dosenlistAction();
		$this->render('dosenlist');
	}
	
	public function dosenhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->dosenUpdate = $this->dosen_serv->dosenHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data dosen", $n_dosen." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Dosen";
		$this->view->hasil = $this->view->dosenUpdate;
		
		$this->dosenlistAction();
		$this->render('dosenlist');
	}
	

}
?>
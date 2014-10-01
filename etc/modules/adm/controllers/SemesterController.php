<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admsemester_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_SemesterController extends Zend_Controller_Action {
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
	   // $ssosemester = new Zend_Session_Namespace('ssosemester');
	   //echo "TEST ".$ssosemester->n_jnssemester_grp." ".$ssosemester->i_semester." ".$ssosemester->i_peg_level_position;
	   $this->semester  = 'cdr';
	   
		$this->semester_serv = Adm_Admsemester_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssosemester = new Zend_Session_Namespace('ssosemester');
	    $this->iduser =$ssosemester->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function semesterjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('semesterjs');
    }
	
	//test OPen report
	//----------------------
	public function semesterlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_jnssemester';
		$sortBy			= 'n_jnssemester';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totSemesterList = $this->semester_serv->cariSemesterList($dataMasukan,0,0);
		$this->view->semesterList = $this->semester_serv->cariSemesterList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function semesterolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iSemester = $_REQUEST['id'];
		
		$this->view->detailSemester = $this->semester_serv->detailSemesterById($iSemester);
	}
	
	public function semesterAction()
	{
		$id		= $_POST['id'];       
		$n_jnssemester		= $_POST['n_jnssemester'];      
		$c_jnssemester		= $_POST['c_jnssemester'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_jnssemester"  	=>$n_jnssemester,"c_jnssemester"  	=>$c_jnssemester);
			
		
		
		$this->view->semesterInsert = $this->semester_serv->semesterInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data semester", $n_jnssemester." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Semester";
		$this->view->hasil = $this->view->semesterInsert;
		
		$this->semesterlistAction();
		$this->render('semesterlist');
	}
	
	public function semesterupdateAction()
	{
		$id		= $_POST['id'];       
		$n_jnssemester		= $_POST['n_jnssemester'];      
		$c_jnssemester		= $_POST['c_jnssemester'];      
		$i_entry 		= $this->semester;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_jnssemester"  	=>$n_jnssemester,"c_jnssemester"  	=>$c_jnssemester);
		
		$this->view->semesterUpdate = $this->semester_serv->semesterUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data semester", $n_jnssemester." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Semester";
		$this->view->hasil = $this->view->semesterUpdate;
		
		$this->semesterlistAction();
		$this->render('semesterlist');
	}
	
	public function semesterhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->semesterUpdate = $this->semester_serv->semesterHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data semester", $n_jnssemester." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Semester";
		$this->view->hasil = $this->view->semesterUpdate;
		
		$this->semesterlistAction();
		$this->render('semesterlist');
	}
	

}
?>
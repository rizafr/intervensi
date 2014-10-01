<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admgroup_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_GroupController extends Zend_Controller_Action {
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
	   //echo "TEST ".$ssogroup->n_group_grp." ".$ssogroup->i_group." ".$ssogroup->i_peg_level_position;
	   $this->group  = 'cdr';
	   
		$this->group_serv = Adm_Admgroup_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroup = new Zend_Session_Namespace('ssogroup');
	    $this->iduser =$ssogroup->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function groupjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('groupjs');
    }
	
	//test OPen report
	//----------------------
	public function grouplistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'role_name';
		$sortBy			= 'role_name';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totGroupList = $this->group_serv->cariGroupList($dataMasukan,0,0);
		$this->view->groupList = $this->group_serv->cariGroupList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function groupolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iGroup = $_REQUEST['role_id'];
		
		$this->view->detailGroup = $this->group_serv->detailGroupById($iGroup);
	}
	
	public function groupAction()
	{
		$role_id		= $_POST['role_id'];       
		$role_name		= $_POST['role_name'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("role_name"  	=>$role_name);
			
		
		
		$this->view->groupInsert = $this->group_serv->groupInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data group", $role_name." (".$role_id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Group";
		$this->view->hasil = $this->view->groupInsert;
		
		$this->grouplistAction();
		$this->render('grouplist');
	}
	
	public function groupupdateAction()
	{
		$role_id		= $_POST['role_id'];       
		$role_name		= $_POST['role_name'];      
		$i_entry 		= $this->group;
		
		
		$dataMasukan = array("role_id"  	=>$role_id,
							"role_name"  	=>$role_name);
		
		$this->view->groupUpdate = $this->group_serv->groupUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data group", $role_name." (".$role_id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Group";
		$this->view->hasil = $this->view->groupUpdate;
		
		$this->grouplistAction();
		$this->render('grouplist');
	}
	
	public function grouphapusAction()
	{
		$role_id 		= $_REQUEST['role_id'];
		
		$dataMasukan = array("role_id" => $role_id);
		
		$this->view->groupUpdate = $this->group_serv->groupHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data group", $role_name." (".$role_id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Group";
		$this->view->hasil = $this->view->groupUpdate;
		
		$this->grouplistAction();
		$this->render('grouplist');
	}
	

}
?>
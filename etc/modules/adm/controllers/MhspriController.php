<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admuser_Service.php";
require_once "service/adm/Adm_Admgroup_Service.php";
require_once "service/adm/Adm_Admstatus_Service.php";
require_once "service/aplikasi/Aplikasi_Referensi_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";


class Adm_MhspriController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
	private $Logfile;	
	private $iduser;
	private $namauser;
		
    public function init() {
		// Local to this controller only; affects all actions, as loaded in init:
		//$this->_helper->viewRenderer->setNoRender(true);
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->user_serv = Adm_Admuser_Service::getInstance();
		$this->group_serv = Adm_Admgroup_Service::getInstance();
		$this->status_serv = Adm_Admstatus_Service::getInstance();
		$this->ref_serv = Aplikasi_Referensi_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroup = new Zend_Session_Namespace('ssogroup');
	    $this->iduser =$ssogroup->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function mhsprijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('mhsprijs');
    }
	
	//test OPen report
	//----------------------
	public function mhsprilistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$kategoriCari 	= $_REQUEST['kategoriCari']; //'username';
		$katakunciCari 	= $_POST['carii'];
		$sortBy			= 'username';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totUserList = $this->user_serv->cariUserList($dataMasukan,0,0,0);
		$this->view->userList = $this->user_serv->cariUserList($dataMasukan,$currentPage, $numToDisplay,$this->view->totUserList);
		
	}
	
	public function mhspriolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iUser = $_REQUEST['user_id'];
		$this->view->detailUser = $this->user_serv->detailUserById($iUser);
		$this->view->groupList = $this->group_serv->getGroupListAll();
		$this->view->statusList = $this->status_serv->getStatusListAll();
		
		$numToDisplay = 20;
		$this->view->userList = $this->ref_serv->getNipList();
	}
	public function mhsprigantipwdAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iUser = $_REQUEST['user_id'];
		$this->view->detailUser = $this->user_serv->detailUserById($iUser);
		$this->view->groupList = $this->group_serv->getGroupListAll();
		$this->view->statusList = $this->status_serv->getStatusListAll();
		
		$numToDisplay = 20;
		$this->view->userList = $this->ref_serv->getNipList();
	}
	public function mhspriAction()
	{
		$user_id		= $_POST['user_id'];       
		$username		= $_POST['username'];      
		$nip			= $_POST['nip'];           
		$kd_status		= $_POST['kd_status'];     
		$c_group		= $_POST['c_group'];
		$password		= $_POST['password'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("username"  	=>$username,
							"nip" 			=>$nip,
							"kd_status" 	=>$kd_status,
							"password"  	=>$password,
							"c_group"  		=>$c_group);
			
		
		
		$this->view->userInsert = $this->user_serv->userInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data user", $username." (".$nip.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "User";
		$this->view->hasil = $this->view->userInsert;
		
		$this->mhsprilistAction();
		$this->render('mhsprilist');
	}
	
	public function mhspriupdateAction()
	{
		$user_id		= $_POST['user_id'];       
		$username		= $_POST['username'];      
		$nip			= $_POST['nip'];           
		$kd_status		= $_POST['kd_status'];     
		$password		= $_POST['password'];
		$c_group		= $_POST['c_group'];
		
		
		$dataMasukan = array("user_id"  	=>$user_id,
							"username"  	=>$username,
							"nip" 			=>$nip,
							"kd_status" 	=>$kd_status,
							"password"  	=>$password,
							"c_group"  		=>$c_group);
		
		$this->view->mhspriUpdate = $this->user_serv->userUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data user", $username." (".$nip.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "User";
		$this->view->hasil = $this->view->mhspriUpdate;
		
		$this->mhsprilistAction();
		$this->render('mhsprilist');
	}
	
	public function mhsprigantipasswdAction()
	{
		$user_id		= $_POST['user_id'];       
		$username		= $_POST['username'];      
		$nip			= $_POST['nip'];           
		$kd_status		= $_POST['kd_status'];     
		$password		= $_POST['password'];
		$c_group		= $_POST['c_group'];
		
		
		$dataMasukan = array("user_id"  	=>$user_id,
							"username"  	=>$username,
							"nip" 			=>$nip,
							"kd_status" 	=>$kd_status,
							"password"  	=>$password,
							"c_group"  		=>$c_group);
		
		$this->view->mhspriUpdate = $this->user_serv->userUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ganti Password", $username." (".$nip.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "User";
		$this->view->hasil = $this->view->mhspriUpdate;
		
		$this->mhsprilistAction();
		$this->render('index');
	}
	
	public function ubahstatusAction()
	{
		$user_id		= $_GET['userid'];       
		$status		= $_GET['status'];      
		
		//$i_entry 		= $this->user;
		
		
		$dataMasukan = array("kd_status"  	=>$status,
							"user_id" => $user_id);
		$this->view->ubahStatus = $this->user_serv->ubahStatus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah status data user", $user_id." (".$status.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "User";
		$this->view->hasil = $this->view->ubahStatus;
		
		$this->userlistAction();
		$this->render('userlist');
	}
	
	public function mhsprihapusAction()
	{
		$user_id 		= $_REQUEST['user_id'];
		//echo $user_id;
		$dataMasukan = array("user_id" => $user_id);
		
		$this->view->userUpdate = $this->user_serv->userHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data user", $user_id." (hapus)");
		$this->view->proses = "3";	
		$this->view->keterangan = "User";
		$this->view->hasil = $this->view->userUpdate;
		
		$this->userlistAction();
		$this->render('userlist');
	}
	public function getnamaPegawaiAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		
		$nip = $_REQUEST['nip'];
		
		$nama = $this->ref_serv->getnamaPegawai($nip);
		
		echo "$nama";	
	}
	public function getpasswdpegawaiAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		
		$passwordlm = $_REQUEST['passwordlm'];
		$user_id = $_REQUEST['user_id'];
		$userid = $this->sso_serv->getDataUser2($user_id,$passwordlm);
		
		echo "$userid";	
	}
}
?>
<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admuser_Service.php";
require_once "service/adm/Adm_Admgroupuser_Service.php";
//require_once "service/adm/Adm_Admstatus_Service.php";
//require_once "service/bak/Bak_Bakdosen_Service.php";
//require_once "service/mh/Mh_Mhmhspri_Service.php";
require_once "service/aplikasi/Aplikasi_Referensi_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";


class Adm_UserController extends Zend_Controller_Action {
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
		$this->group_serv = Adm_Admgroupuser_Service::getInstance();
		//$this->dosen_serv = Bak_Bakdosen_Service::getInstance();
		//$this->mahasiswa_serv = Mh_Mhmhspri_Service::getInstance();
		//$this->status_serv = Adm_Admstatus_Service::getInstance();
		//$this->ref_serv = Aplikasi_Referensi_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroup = new Zend_Session_Namespace('ssogroup');
	    $this->iduser =$ssogroup->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		///tambahan hendar
		$ssouserid = new Zend_Session_Namespace('ssouserid');
		$this->user_id =$ssouserid->user_id;
		$this->id_user =$ssouserid->id;
		$ssousergroup = new Zend_Session_Namespace('ssousergroup');
		$this->group_user =$ssousergroup->c_group;
		//		
    }
	
    public function indexAction() {
	   
    }
	
	public function userjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('userjs');
    }
	
	//test OPen report
	//----------------------
	public function userlistAction()
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
	public function namadosenAction() {
	//	$this->view->namadosen = $this->dosen_serv->getNamaDosen();
    }
	public function namamahasiswaAction() {
		//$this->view->namamahasiswa = $this->mahasiswa_serv->getNamaMahasiswa();
    }
	
	public function userolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$id = $_REQUEST['id'];
        $this->view->c_group = $_REQUEST['c_group'];
		$this->view->detailUser = $this->user_serv->detailUserById($id);
		$this->view->groupList = $this->group_serv->getGroupuserListAll();
		//$this->view->statusList = $this->status_serv->getStatusListAll();
		//$numToDisplay = 20;
		//$this->view->userList = $this->ref_serv->getNipList();
	}
	public function usergantipwdAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$id = $_REQUEST['id'];
		$this->view->detailUser = $this->user_serv->detailUserById($id);
		$this->view->groupList = $this->group_serv->getGroupuserListAll();
		//$this->view->statusList = $this->status_serv->getStatusListAll();
		
		//$this->view->userList = $this->ref_serv->getNipList();
	}
	public function userAction()
	{
		$user_id		= $_POST['user_id'];       
		$username		= $_POST['username'];      
		$name		= $_POST['name'];      
		$kd_status		= $_POST['kd_status'];     
		$c_group		= $_POST['c_group'];
		$password		= $_POST['password'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("username"  	=>$username,
							"name"  	=>$name,
							"user_id" 	    =>$user_id,
							"kd_status" 	=>$kd_status,
							"password"  	=>$password,
							"c_group"  		=>$c_group);
			
		
		
		$this->view->userInsert = $this->user_serv->userInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data user", $username." (".$nip.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "User";
		$this->view->hasil = $this->view->userInsert;
		
		$this->userlistAction();
		$this->render('userlist');
	}
	
	public function userupdateAction()
	{
		$id				= $_POST['id'];       
		$user_id		= $_POST['user_id'];       
		$username		= $_POST['username'];      
		$name		= $_POST['name'];      
		$kd_status		= $_POST['kd_status'];     
		$password		= $_POST['password'];
		$c_group		= $_POST['c_group'];
		
		
		$dataMasukan = array("id"  	=>$id,
							"user_id"  	=>$user_id,
							"username"  	=>$username,
							"name"  	=>$name,
							"kd_status" 	=>$kd_status,
							"password"  	=>$password,
							"c_group"  		=>$c_group);
		
		$this->view->userUpdate = $this->user_serv->userUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data user", $username." (".$nip.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "User";
		$this->view->hasil = $this->view->userUpdate;
		
		$this->userlistAction();
		$this->render('userlist');
	}
	
	public function usergantipasswdAction()
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
		
		$this->view->userUpdate = $this->user_serv->userUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ganti Password", $username." (".$nip.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "User";
		$this->view->hasil = $this->view->userUpdate;
		
		$this->userlistAction();
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
	
	public function userhapusAction()
	{
		$id 		= $_REQUEST['id'];
		//echo $user_id;
		$dataMasukan = array("id" => $id);
		
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
	
///tambahan hendar 03032010

	public function gantikatasandiAction()
	{
		//$user_id =$this->user_id;
		//$group_user =$this->group_user;
        $user_id=$_GET['userid'];
		$cari= " and id='$user_id' and 1=1 ";
		$this->view->datauser = $this->user_serv->getTmUser($cari);
	}
	
    public function gantikatasandijsAction() {
	   header('content-type : text/javascript');
	   $this->render('gantikatasandijs');
    }	

	public function katasandiubahAction()
	{
		//$user_id =$this->id_user;
		//$group_user =$this->group_user;

		$id = $_GET['id'];
		$passwordlama=$_GET['passwordlama'];
		$passwordasal=$_GET['passwordasal'];
		$passwordbaru=$_GET['passwordbaru'];
		$confirmpassword=$_GET['confirmpassword'];
		
		if ($passwordasal!=md5($passwordlama))
		{
			?>
			<script>
				alert ("Kata Sandi lama tidak sesuai");
			</script>
			<?
		}
		else
		{
			if ($passwordbaru!=$confirmpassword)
			{
			?>
			<script>
				alert ("Konfirmasi Kata Sandi Salah!.....");
			</script>
			<?			
			}
			else
			{
				$passwordbaru=md5($passwordbaru);
				$MaintainData = array(
									"id"=>$id,
									"password"=>$passwordbaru);	
				$hasil = $this->user_serv->ubahPasswd($MaintainData);	
				$parpesan="Ubah Kata Sandi ";
				$pesan=$parpesan." ".$hasil;
				$this->view->pesan = $pesan;
				$this->view->pesancek = $hasil;	
			?>
			<script>
				location.href="#top";
				doCounter(5);
			</script>
			<?		
			}
			
		
		}
		$this->view->passwordlama =$_GET['passwordlama'];
		$this->view->passwordbaru =$_GET['passwordbaru'];
		$this->view->confirmpassword =$_GET['confirmpassword'];

		$cari= " and id='$id' and 1=1 ";	
		$this->view->datauser = $this->user_serv->getTmUser($cari);
		
		$this->_helper->viewRenderer('gantikatasandi');
	}	
////		
}
?>
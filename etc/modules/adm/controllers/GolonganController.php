<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admgolongan_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_GolonganController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
	private $Logfile;	
	private $iduser;
	private $namauser;
	
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
		
		 
	    $this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroup1 = new Zend_Session_Namespace('ssogroup1');
	    $this->iduser =$ssogroup1->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		
		$this->golongan_serv = Adm_Admgolongan_Service::getInstance();
		$this->Logfile = new logfile;
		
	}
	
    public function indexAction() {
	   
    }
	
	public function golonganjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('golonganjs');
    }
	
	//test OPen report
	//----------------------
	public function golonganlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			   
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari1 	= 'nm_golongan';
		$kategoriCari2 	= 'pangkat';
		$sortBy			= 'nm_golongan';
		$sortBy			= 'pangkat';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari1" => $kategoriCari1,
							"kategoriCari2" => $kategoriCari2,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totGolonganList = $this->golongan_serv->cariGolonganList($dataMasukan,0,0);
		$this->view->golonganList = $this->golongan_serv->cariGolonganList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function golonganolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$kd_golongan = $_REQUEST['kd_golongan'];
		//echo "iuser --->".$iUser;
		$this->view->detailGolongan = $this->golongan_serv->detailGolonganById($kd_golongan);
	}
	
	public function golonganAction()
	{
		$nm_golongan		= $_POST['nm_golongan'];      
		$pangkat			= $_POST['pangkat'];      
		
		$dataMasukan = array("nm_golongan"				=>$nm_golongan,
							"pangkat"					=>$pangkat);
		
		$this->view->golonganInsert = $this->golongan_serv->golonganInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data golongan", $nm_golongan." (".$pangkat.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Golongan";
		$this->view->hasil = $hasil;
		
		$this->golonganlistAction();
		$this->render('golonganlist');
	}
	
	public function golonganupdateAction()
	{
		$kd_golongan		= $_POST['kd_golongan'];       
		$nm_golongan		= $_POST['nm_golongan'];      
		$pangkat			= $_POST['pangkat'];      
		
		$dataMasukan = array("kd_golongan"  	=>$kd_golongan,
							"nm_golongan"	 	=>$nm_golongan,
							"pangkat"		 	=>$pangkat);
		
		$this->view->golonganUpdate = $this->golongan_serv->golonganUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Update data golongan", $kd_golongan." (".$nm_golongan.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Golongan";
		$this->view->hasil = $hasil;
		
		$this->golonganlistAction();
		$this->render('golonganlist');
	}
	
	public function golonganhapusAction()
	{
		$kd_golongan 		= $_REQUEST['kd_golongan'];
		
		$dataMasukan = array("kd_golongan" => $kd_golongan);
		
		$this->view->golonganUpdate = $this->golongan_serv->golonganHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data golongan", $kd_golongan." (".$nm_golongan.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Golongan";
		$this->view->hasil = $hasil;
		
		$this->golonganlistAction();
		$this->render('golonganlist');
	}
	

}
?>
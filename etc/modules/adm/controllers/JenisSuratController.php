<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_AdmjenisSurat_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_JenisSuratController extends Zend_Controller_Action {
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
	   
		$this->jenisSurat_serv = Adm_AdmjenisSurat_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroup = new Zend_Session_Namespace('ssogroup');
	    $this->iduser =$ssogroup->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function jenisSuratjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('jenisSuratjs');
    }
	
	//test OPen report
	//----------------------
	public function jenisSuratlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_srtjenis';
		$sortBy			= 'n_srtjenis';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totJenisSuratList = $this->jenisSurat_serv->cariJenisSuratList($dataMasukan,0,0);
		$this->view->jenisSuratList = $this->jenisSurat_serv->cariJenisSuratList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function jenisSuratolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$c_srtjenis = $_REQUEST['c_srtjenis'];
		//echo "iuser --->".$iUser;
		$this->view->detailJenisSurat = $this->jenisSurat_serv->detailJenisSuratById($c_srtjenis);
	}
	
	public function jenisSuratAction()
	{
		$c_srtjenis		= $_POST['c_srtjenis'];       
		$n_srtjenis		= $_POST['n_srtjenis'];      
		
		$dataMasukan = array("c_srtjenis"  	=>$c_srtjenis,
							"n_srtjenis"  	=>$n_srtjenis);
		
		$this->view->jenisSuratInsert = $this->jenisSurat_serv->jenisSuratInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data jenis surat", $n_srtjenis." (".$c_srtjenis.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Jenis Surat";
		$this->view->hasil = $this->view->jenisSuratInsert;
		
		$this->jenisSuratlistAction();
		$this->render('jenisSuratlist');
	}
	
	public function jenisSuratupdateAction()
	{
		$c_srtjenis		= $_POST['c_srtjenis'];       
		$n_srtjenis		= $_POST['n_srtjenis'];      
		
		$dataMasukan = array("c_srtjenis"  	=>$c_srtjenis,
							"n_srtjenis"  	=>$n_srtjenis);
		
		$this->view->jenisSuratUpdate = $this->jenisSurat_serv->jenisSuratUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data jenis surat", $n_srtjenis." (".$c_srtjenis.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Jenis Surat";
		$this->view->hasil = $this->view->jenisSuratUpdate;
		
		$this->jenisSuratlistAction();
		$this->render('jenisSuratlist');
	}
	
	public function jenisSurathapusAction()
	{
		$c_srtjenis 		= $_REQUEST['c_srtjenis'];
		
		$dataMasukan = array("c_srtjenis" => $c_srtjenis);
		
		$this->view->jenisSuratUpdate = $this->jenisSurat_serv->jenisSuratHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data jenis surat", $n_srtjenis." (".$c_srtjenis.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Jenis Surat";
		$this->view->hasil = $this->view->jenisSuratUpdate;
		
		$this->jenisSuratlistAction();
		$this->render('jenisSuratlist');
	}
	

}
?>
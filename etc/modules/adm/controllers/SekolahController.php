<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admsekolah_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_SekolahController extends Zend_Controller_Action {
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
	   // $ssosekolah = new Zend_Session_Namespace('ssosekolah');
	   //echo "TEST ".$ssosekolah->n_sekolah_grp." ".$ssosekolah->i_sekolah." ".$ssosekolah->i_peg_level_position;
	   $this->sekolah  = 'cdr';
	   
		$this->sekolah_serv = Adm_Admsekolah_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssosekolah = new Zend_Session_Namespace('ssosekolah');
	    $this->iduser =$ssosekolah->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function sekolahjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('sekolahjs');
    }
	
	//test OPen report
	//----------------------
	public function sekolahlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_sekolah';
		$sortBy			= 'n_sekolah';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totSekolahList = $this->sekolah_serv->cariSekolahList($dataMasukan,0,0);
		$this->view->sekolahList = $this->sekolah_serv->cariSekolahList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function sekolaholahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iSekolah = $_REQUEST['id'];
		
		$this->view->detailSekolah = $this->sekolah_serv->detailSekolahById($iSekolah);
	}
	
	public function sekolahAction()
	{
		$id		= $_POST['id'];       
		$n_sekolah		= $_POST['n_sekolah'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_sekolah"  	=>$n_sekolah);
			
		
		
		$this->view->sekolahInsert = $this->sekolah_serv->sekolahInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data sekolah", $n_sekolah." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Sekolah";
		$this->view->hasil = $this->view->sekolahInsert;
		
		$this->sekolahlistAction();
		$this->render('sekolahlist');
	}
	
	public function sekolahupdateAction()
	{
		$id		= $_POST['id'];       
		$n_sekolah		= $_POST['n_sekolah'];      
		$i_entry 		= $this->sekolah;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_sekolah"  	=>$n_sekolah);
		
		$this->view->sekolahUpdate = $this->sekolah_serv->sekolahUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data sekolah", $n_sekolah." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Sekolah";
		$this->view->hasil = $this->view->sekolahUpdate;
		
		$this->sekolahlistAction();
		$this->render('sekolahlist');
	}
	
	public function sekolahhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->sekolahUpdate = $this->sekolah_serv->sekolahHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data sekolah", $n_sekolah." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Sekolah";
		$this->view->hasil = $this->view->sekolahUpdate;
		
		$this->sekolahlistAction();
		$this->render('sekolahlist');
	}
	

}
?>
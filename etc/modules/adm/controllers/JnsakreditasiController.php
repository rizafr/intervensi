<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admjnsakreditasi_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_JnsakreditasiController extends Zend_Controller_Action {
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
	   // $ssojnsakreditasi = new Zend_Session_Namespace('ssojnsakreditasi');
	   //echo "TEST ".$ssojnsakreditasi->n_jnsakreditasi_grp." ".$ssojnsakreditasi->i_jnsakreditasi." ".$ssojnsakreditasi->i_peg_level_position;
	   $this->jnsakreditasi  = 'cdr';
	   
		$this->jnsakreditasi_serv = Adm_Admjnsakreditasi_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssojnsakreditasi = new Zend_Session_Namespace('ssojnsakreditasi');
	    $this->iduser =$ssojnsakreditasi->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function jnsakreditasijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('jnsakreditasijs');
    }
	
	//test OPen report
	//----------------------
	public function jnsakreditasilistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_akreditasi';
		$sortBy			= 'n_akreditasi';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totJnsakreditasiList = $this->jnsakreditasi_serv->cariJnsakreditasiList($dataMasukan,0,0);
		$this->view->jnsakreditasiList = $this->jnsakreditasi_serv->cariJnsakreditasiList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function jnsakreditasiolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iJnsakreditasi = $_REQUEST['id'];
		
		$this->view->detailJnsakreditasi = $this->jnsakreditasi_serv->detailJnsakreditasiById($iJnsakreditasi);
	}
	
	public function jnsakreditasiAction()
	{
		$id		= $_POST['id'];       
		$n_akreditasi		= $_POST['n_akreditasi'];      
		$c_akreditasi		= $_POST['c_akreditasi'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_akreditasi"  	=>$n_akreditasi,"c_akreditasi"  	=>$c_akreditasi);
			
		$this->view->jnsakreditasiInsert = $this->jnsakreditasi_serv->jnsakreditasiInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data jnsakreditasi", $n_jnsakreditasi." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Jnsakreditasi";
		$this->view->hasil = $this->view->jnsakreditasiInsert;
		
		$this->jnsakreditasilistAction();
		$this->render('jnsakreditasilist');
	}
	
	public function jnsakreditasiupdateAction()
	{
		$id		= $_POST['id'];       
		$n_akreditasi		= $_POST['n_akreditasi'];      
		$c_akreditasi		= $_POST['c_akreditasi'];      
		$i_entry 		= $this->jnsakreditasi;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_akreditasi"  	=>$n_akreditasi,
							"c_akreditasi"  	=>$c_akreditasi);
		
		$this->view->jnsakreditasiUpdate = $this->jnsakreditasi_serv->jnsakreditasiUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data jnsakreditasi", $n_jnsakreditasi." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Jnsakreditasi";
		$this->view->hasil = $this->view->jnsakreditasiUpdate;
		
		$this->jnsakreditasilistAction();
		$this->render('jnsakreditasilist');
	}
	
	public function jnsakreditasihapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->jnsakreditasiUpdate = $this->jnsakreditasi_serv->jnsakreditasiHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data jnsakreditasi", $n_jnsakreditasi." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Jnsakreditasi";
		$this->view->hasil = $this->view->jnsakreditasiUpdate;
		
		$this->jnsakreditasilistAction();
		$this->render('jnsakreditasilist');
	}
	

}
?>
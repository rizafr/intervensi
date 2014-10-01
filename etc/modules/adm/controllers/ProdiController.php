<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admprodi_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_ProdiController extends Zend_Controller_Action {
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
	   // $ssoprodi = new Zend_Session_Namespace('ssoprodi');
	   //echo "TEST ".$ssoprodi->n_prodi_grp." ".$ssoprodi->i_prodi." ".$ssoprodi->i_peg_level_position;
	   $this->prodi  = 'cdr';
	   
		$this->prodi_serv = Adm_Admprodi_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssoprodi = new Zend_Session_Namespace('ssoprodi');
	    $this->iduser =$ssoprodi->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function prodijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('prodijs');
    }
	
	//test OPen report
	//----------------------
	public function prodilistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_prodi';
		$sortBy			= 'n_prodi';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totProdiList = $this->prodi_serv->cariProdiList($dataMasukan,0,0);
		$this->view->prodiList = $this->prodi_serv->cariProdiList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function prodiolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iProdi = $_REQUEST['id'];
		
		$this->view->detailProdi = $this->prodi_serv->detailProdiById($iProdi);
	}
	
	public function prodiAction()
	{
		$id		= $_POST['id'];       
		$n_prodi		= $_POST['n_prodi'];      
		$c_prodi		= $_POST['c_prodi'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_prodi"  	=>$n_prodi,
							 "c_prodi"  	=>$c_prodi);
		//var_dump($dataMasukan);
		$this->view->prodiInsert = $this->prodi_serv->prodiInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data prodi", $n_prodi." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Prodi";
		$this->view->hasil = $this->view->prodiInsert;
		$this->prodilistAction();
		$this->render('prodilist');
	}
	
	public function prodiupdateAction()
	{
		$id		= $_POST['id'];       
		$n_prodi		= $_POST['n_prodi'];      
		$c_prodi		= $_POST['c_prodi'];      
		$i_entry 		= $this->prodi;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_prodi"  	=>$n_prodi,
							 "c_prodi"  	=>$c_prodi);
		
		$this->view->prodiUpdate = $this->prodi_serv->prodiUpdate($dataMasukan);
		//var_dump($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data prodi", $n_prodi." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Prodi";
		$this->view->hasil = $this->view->prodiUpdate;
		
		$this->prodilistAction();
		$this->render('prodilist');
	}
	
	public function prodihapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->prodiUpdate = $this->prodi_serv->prodiHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data prodi", $n_prodi." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Prodi";
		$this->view->hasil = $this->view->prodiUpdate;
		
		$this->prodilistAction();
		$this->render('prodilist');
	}
	

}
?>
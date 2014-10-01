<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admkabupaten_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_KabupatenController extends Zend_Controller_Action {
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
	   // $ssokabupaten = new Zend_Session_Namespace('ssokabupaten');
	   //echo "TEST ".$ssokabupaten->n_kabE_grp." ".$ssokabupaten->i_kabupaten." ".$ssokabupaten->i_peg_level_position;
	   $this->kabupaten  = 'cdr';
	   
		$this->kabupaten_serv = Adm_Admkabupaten_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssokabupaten = new Zend_Session_Namespace('ssokabupaten');
	    $this->iduser =$ssokabupaten->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function kabupatenjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('kabupatenjs');
    }
	
	//test OPen report
	//----------------------
	public function kabupatenlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		if($_REQUEST['param1']){
			$this->view->katakunciCari 	= $_REQUEST['param1'];
		}
		else{
			$this->view->katakunciCari 	= $_REQUEST['carii'];
		}
		$kategoriCari 	= 'n_kab';
		$sortBy			= 'n_kab';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $this->view->katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKabupatenList = $this->kabupaten_serv->cariKabupatenList($dataMasukan,0,0,0);
		$this->view->kabupatenList = $this->kabupaten_serv->cariKabupatenList($dataMasukan,$currentPage, $numToDisplay,$this->view->totKabupatenList);		
	}
	
	public function kabupatenolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iKabupaten = $_REQUEST['id'];
		
		$this->view->detailKabupaten = $this->kabupaten_serv->detailKabupatenById($iKabupaten);
	}
	
	public function kabupatenAction()
	{
		$id			= $_POST['id'];       
		$id_prop		= $_POST['id_prop'];       
		$n_kab		= $_POST['n_kab'];       
		$c_kab			= $_POST['c_kab'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("id"  		=>$id,
							"id_prop"  		=>$id_prop,
							"n_kab"  		=>$n_kab,
							"c_kab"  		=>$c_kab	
							);
		
		$this->view->kabupatenInsert = $this->kabupaten_serv->kabupatenInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data kabupaten", $n_kab." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Kabupaten";
		$this->view->hasil = $this->view->kabupatenInsert;
		
		$this->kabupatenlistAction();
		$this->render('kabupatenlist');
	}
	
	public function kabupatenupdateAction()
	{
		$id			= $_POST['id'];       
		$id_prop		= $_POST['id_prop'];       
		$n_kab		= $_POST['n_kab'];       
		$c_kab			= $_POST['c_kab'];      
		$i_entry 		= $this->kabupaten;
		
		$dataMasukan = array("id"  		=>$id,
							"id_prop"  		=>$id_prop,
							"n_kab"		=>$n_kab,
							"c_kab"  		=>$c_kab	
							);
		
		$this->view->kabupatenUpdate = $this->kabupaten_serv->kabupatenUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data kabupaten", $n_kab." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Kabupaten";
		$this->view->hasil = $this->view->kabupatenUpdate;
		
		$this->kabupatenlistAction();
		$this->render('kabupatenlist');
	}
	
	public function kabupatenhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->kabupatenUpdate = $this->kabupaten_serv->kabupatenHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data kabupaten", $n_kab." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Kabupaten";
		$this->view->hasil = $this->view->kabupatenUpdate;
		
		$this->kabupatenlistAction();
		$this->render('kabupatenlist');
	}
	

}
?>
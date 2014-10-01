<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admgroupuser_Service.php";
require_once "service/adm/Adm_Admprodi_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_GroupuserController extends Zend_Controller_Action {
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
	   // $ssogroupuser = new Zend_Session_Namespace('ssogroupuser');
	   //echo "TEST ".$ssogroupuser->n_groupuser_grp." ".$ssogroupuser->i_groupuser." ".$ssogroupuser->i_peg_level_position;
	   $this->groupuser  = 'cdr';
	   
		$this->prodi_serv = Adm_Admprodi_Service::getInstance();
		$this->groupuser_serv = Adm_Admgroupuser_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssogroupuser = new Zend_Session_Namespace('ssogroupuser');
	    $this->iduser =$ssogroupuser->user_id;
	    $this->view->n_namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function groupuserjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('groupuserjs');
    }
	
	//test OPen report
	//----------------------
	public function groupuserlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_nama';
		$sortBy			= 'n_nama';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totGroupuserList = $this->groupuser_serv->cariGroupuserList($dataMasukan,0,0);
		$this->view->groupuserList = $this->groupuser_serv->cariGroupuserList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function groupuserolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iGroupuser = $_REQUEST['id'];
		$this->view->prodiList = $this->prodi_serv->getProdiListAll();		
		$this->view->detailGroupuser = $this->groupuser_serv->detailGroupuserById($iGroupuser);
	}
	
	public function groupuserAction()
	{
		      
		$n_nama				= $_POST['n_nama'];      
		$e_ket				= $_POST['e_ket'];      
		$keterangan 		= $_POST['keterangan'];     
		$n_level			= $_POST['n_level']; 
		
		$dataMasukan = array("n_nama"  	=>$n_nama,
							 "n_level"  	=>$n_level,
							 "keterangan"  	=>$keterangan,
							 "e_ket"  	=>$e_ket);
			
		
		
		$this->view->groupuserInsert = $this->groupuser_serv->groupuserInsert($dataMasukan);
		$this->Logfile->createLog($this->view->n_namauser, "Insert data groupuser", $n_nama." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Groupuser";
		$this->view->hasil = $this->view->groupuserInsert;
		
		$this->groupuserlistAction();
		$this->render('groupuserlist');
	}
	
	public function groupuserupdateAction()
	{
		$id			= $_POST['id'];       
		$n_nama		= $_POST['n_nama'];      
		$e_ket		= $_POST['e_ket'];      
		$i_entry 	= $this->groupuser;
		$keterangan 		= $_POST['keterangan'];     
		$n_level			= $_POST['n_level']; 
		
		$dataMasukan = array("id"  	=>$id,
							"n_level"  	=>$n_level,
							 "keterangan"  	=>$keterangan,
							 "e_ket"  	=>$e_ket,
							"n_nama"  	=>$n_nama);
		
		$this->view->groupuserUpdate = $this->groupuser_serv->groupuserUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->n_namauser, "Ubah data groupuser", $n_nama." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Groupuser";
		$this->view->hasil = $this->view->groupuserUpdate;
		
		$this->groupuserlistAction();
		$this->render('groupuserlist');
	}
	
	public function groupuserhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->groupuserUpdate = $this->groupuser_serv->groupuserHapus($dataMasukan);
		$this->Logfile->createLog($this->view->n_namauser, "Hapus data groupuser", $n_nama." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Groupuser";
		$this->view->hasil = $this->view->groupuserUpdate;
		
		$this->groupuserlistAction();
		$this->render('groupuserlist');
	}
	

}
?>
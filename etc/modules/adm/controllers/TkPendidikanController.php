<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admtkpendidikan_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_TkPendidikanController extends Zend_Controller_Action {
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
	   // $ssotkpendidikan = new Zend_Session_Namespace('ssotkpendidikan');
	   //echo "TEST ".$ssotkpendidikan->n_tingkatpendE_grp." ".$ssotkpendidikan->i_tkpendidikan." ".$ssotkpendidikan->i_peg_level_position;
	   $this->tkpendidikan  = 'cdr';
	   
		$this->tkpendidikan_serv = Adm_Admtkpendidikan_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssotkpendidikan = new Zend_Session_Namespace('ssotkpendidikan');
	    $this->iduser =$ssotkpendidikan->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function tkpendidikanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('tkpendidikanjs');
    }
	
	//test OPen report
	//----------------------
	public function tkpendidikanlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_tingkatpend';
		$sortBy			= 'n_tingkatpend';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totTkPendidikanList = $this->tkpendidikan_serv->cariTkPendidikanList($dataMasukan,0,0);
		$this->view->tkpendidikanList = $this->tkpendidikan_serv->cariTkPendidikanList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function tkpendidikanolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iTkPendidikan = $_REQUEST['id'];
		
		$this->view->detailTkPendidikan = $this->tkpendidikan_serv->detailTkPendidikanById($iTkPendidikan);
	}
	
	public function tkpendidikanAction()
	{
		$id			= $_POST['id'];       
		$c_tingkatpend			= $_POST['c_tingkatpend'];      
		$n_tingkatpend		= $_POST['n_tingkatpend'];       
		$e_ket			= $_POST['e_ket'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("id"  		=>$id,
							"c_tingkatpend"  		=>$c_tingkatpend,
							"n_tingkatpend"  		=>$n_tingkatpend,
							"e_ket"  		=>$e_ket	
							);
		
		$this->view->tkpendidikanInsert = $this->tkpendidikan_serv->tkpendidikanInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data tkpendidikan", $n_tingkatpend." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "TkPendidikan";
		$this->view->hasil = $this->view->tkpendidikanInsert;
		
		$this->tkpendidikanlistAction();
		$this->render('tkpendidikanlist');
	}
	
	public function tkpendidikanupdateAction()
	{
		$id			= $_POST['id'];       
		$c_tingkatpend			= $_POST['c_tingkatpend'];      
		$n_tingkatpend		= $_POST['n_tingkatpend'];       
		$e_ket			= $_POST['e_ket'];      
		$i_entry 		= $this->tkpendidikan;
		
		$dataMasukan = array("id"  		=>$id,
							"c_tingkatpend"  		=>$c_tingkatpend,
							"n_tingkatpend"		=>$n_tingkatpend,
							"e_ket"  		=>$e_ket	
							);
		
		$this->view->tkpendidikanUpdate = $this->tkpendidikan_serv->tkpendidikanUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data tkpendidikan", $n_tingkatpend." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "TkPendidikan";
		$this->view->hasil = $this->view->tkpendidikanUpdate;
		
		$this->tkpendidikanlistAction();
		$this->render('tkpendidikanlist');
	}
	
	public function tkpendidikanhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->tkpendidikanUpdate = $this->tkpendidikan_serv->tkpendidikanHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data tkpendidikan", $n_tingkatpend." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "TkPendidikan";
		$this->view->hasil = $this->view->tkpendidikanUpdate;
		
		$this->tkpendidikanlistAction();
		$this->render('tkpendidikanlist');
	}
	

}
?>
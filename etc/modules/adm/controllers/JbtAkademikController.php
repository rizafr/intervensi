<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admjbtakademik_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_JbtAkademikController extends Zend_Controller_Action {
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
	   // $ssojbtakademik = new Zend_Session_Namespace('ssojbtakademik');
	   //echo "TEST ".$ssojbtakademik->n_jbtakademikE_grp." ".$ssojbtakademik->i_jbtakademik." ".$ssojbtakademik->i_peg_level_position;
	   $this->jbtakademik  = 'cdr';
	   
		$this->jbtakademik_serv = Adm_Admjbtakademik_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssojbtakademik = new Zend_Session_Namespace('ssojbtakademik');
	    $this->iduser =$ssojbtakademik->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function jbtakademikjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('jbtakademikjs');
    }
	
	//test OPen report
	//----------------------
	public function jbtakademiklistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_jbtakademik';
		$sortBy			= 'n_jbtakademik';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totJbtAkademikList = $this->jbtakademik_serv->cariJbtAkademikList($dataMasukan,0,0);
		$this->view->jbtakademikList = $this->jbtakademik_serv->cariJbtAkademikList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function jbtakademikolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iJbtAkademik = $_REQUEST['id'];
		
		$this->view->detailJbtAkademik = $this->jbtakademik_serv->detailJbtAkademikById($iJbtAkademik);
	}
	
	public function jbtakademikAction()
	{
		$id			= $_POST['id'];       
		$c_jbtakademik			= $_POST['c_jbtakademik'];      
		$n_jbtakademik		= $_POST['n_jbtakademik'];       
		$e_ket			= $_POST['e_ket'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("id"  		=>$id,
							"c_jbtakademik"  		=>$c_jbtakademik,
							"n_jbtakademik"  		=>$n_jbtakademik,
							"e_ket"  		=>$e_ket	
							);
		
		$this->view->jbtakademikInsert = $this->jbtakademik_serv->jbtakademikInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data jbtakademik", $n_jbtakademik." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "JbtAkademik";
		$this->view->hasil = $this->view->jbtakademikInsert;
		
		$this->jbtakademiklistAction();
		$this->render('jbtakademiklist');
	}
	
	public function jbtakademikupdateAction()
	{
		$id			= $_POST['id'];       
		$c_jbtakademik			= $_POST['c_jbtakademik'];      
		$n_jbtakademik		= $_POST['n_jbtakademik'];       
		$e_ket			= $_POST['e_ket'];      
		$i_entry 		= $this->jbtakademik;
		
		$dataMasukan = array("id"  		=>$id,
							"c_jbtakademik"  		=>$c_jbtakademik,
							"n_jbtakademik"		=>$n_jbtakademik,
							"e_ket"  		=>$e_ket	
							);
		
		$this->view->jbtakademikUpdate = $this->jbtakademik_serv->jbtakademikUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data jbtakademik", $n_jbtakademik." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "JbtAkademik";
		$this->view->hasil = $this->view->jbtakademikUpdate;
		
		$this->jbtakademiklistAction();
		$this->render('jbtakademiklist');
	}
	
	public function jbtakademikhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->jbtakademikUpdate = $this->jbtakademik_serv->jbtakademikHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data jbtakademik", $n_jbtakademik." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "JbtAkademik";
		$this->view->hasil = $this->view->jbtakademikUpdate;
		
		$this->jbtakademiklistAction();
		$this->render('jbtakademiklist');
	}
	

}
?>
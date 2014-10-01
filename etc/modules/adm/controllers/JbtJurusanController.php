<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admjbtjurusan_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_JbtJurusanController extends Zend_Controller_Action {
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
	   // $ssojbtjurusan = new Zend_Session_Namespace('ssojbtjurusan');
	   //echo "TEST ".$ssojbtjurusan->n_jbtJurusan_grp." ".$ssojbtjurusan->i_jbtjurusan." ".$ssojbtjurusan->i_peg_level_position;
	   $this->jbtjurusan  = 'cdr';
	   
		$this->jbtjurusan_serv = Adm_Admjbtjurusan_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssojbtjurusan = new Zend_Session_Namespace('ssojbtjurusan');
	    $this->iduser =$ssojbtjurusan->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function jbtjurusanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('jbtjurusanjs');
    }
	
	//test OPen report
	//----------------------
	public function jbtjurusanlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_jbtJurusan';
		$sortBy			= 'n_jbtJurusan';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totJbtJurusanList = $this->jbtjurusan_serv->cariJbtJurusanList($dataMasukan,0,0);
		$this->view->jbtjurusanList = $this->jbtjurusan_serv->cariJbtJurusanList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function jbtjurusanolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iJbtJurusan = $_REQUEST['id'];
		
		$this->view->detailJbtJurusan = $this->jbtjurusan_serv->detailJbtJurusanById($iJbtJurusan);
	}
	
	public function jbtjurusanAction()
	{
		$id		= $_POST['id'];       
		$c_jurusan		= $_POST['c_jurusan'];      
		$c_jbtJurusan		= $_POST['c_jbtJurusan'];      
		$n_jbtJurusan		= $_POST['n_jbtJurusan'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("c_jurusan"  	=>$c_jurusan,
							"n_jbtJurusan"  	=>$n_jbtJurusan,
							"c_jbtJurusan"  	=>$c_jbtJurusan);
			
		
		
		$this->view->jbtjurusanInsert = $this->jbtjurusan_serv->jbtjurusanInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data jbtjurusan", $n_jbtJurusan." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Jabatan Jurusan";
		$this->view->hasil = $this->view->jbtjurusanInsert;
		
		$this->jbtjurusanlistAction();
		$this->render('jbtjurusanlist');
	}
	
	public function jbtjurusanupdateAction()
	{
		$id		= $_POST['id'];       
		$n_jbtJurusan		= $_POST['n_jbtJurusan'];      
		$c_jbtJurusan		= $_POST['c_jbtJurusan'];      
		$c_jurusan		= $_POST['c_jurusan'];      
		$i_entry 		= $this->jbtjurusan;
		
		
		$dataMasukan = array("id"  	=>$id,
							"c_jurusan"  	=>$c_jurusan,
							"n_jbtJurusan"  	=>$n_jbtJurusan,
							"c_jbtJurusan"  	=>$c_jbtJurusan);
		
		$this->view->jbtjurusanUpdate = $this->jbtjurusan_serv->jbtjurusanUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data jbtjurusan", $n_jbtJurusan." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Jabatan Jurusan";
		$this->view->hasil = $this->view->jbtjurusanUpdate;
		
		$this->jbtjurusanlistAction();
		$this->render('jbtjurusanlist');
	}
	
	public function jbtjurusanhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->jbtjurusanUpdate = $this->jbtjurusan_serv->jbtjurusanHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data jbtjurusan", $n_jbtJurusan." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Jabatan Jurusan";
		$this->view->hasil = $this->view->jbtjurusanUpdate;
		
		$this->jbtjurusanlistAction();
		$this->render('jbtjurusanlist');
	}
	

}
?>
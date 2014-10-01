<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admmatakuliah_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_MatakuliahController extends Zend_Controller_Action {
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
	   // $ssomatakuliah = new Zend_Session_Namespace('ssomatakuliah');
	   //echo "TEST ".$ssomatakuliah->n_matakuliahE_grp." ".$ssomatakuliah->i_matakuliah." ".$ssomatakuliah->i_peg_level_position;
	   $this->matakuliah  = 'cdr';
	   
		$this->matakuliah_serv = Adm_Admmatakuliah_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssomatakuliah = new Zend_Session_Namespace('ssomatakuliah');
	    $this->iduser =$ssomatakuliah->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function matakuliahjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('matakuliahjs');
    }
	
	//test OPen report
	//----------------------
	public function matakuliahlistAction()
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
		
		
		$kategoriCari 	= 'n_matakuliah';
		$sortBy			= 'n_matakuliah';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $this->view->katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totMatakuliahList = $this->matakuliah_serv->cariMatakuliahList($dataMasukan,0,0,0);
		$this->view->matakuliahList = $this->matakuliah_serv->cariMatakuliahList($dataMasukan,$currentPage, $numToDisplay,$this->view->totMatakuliahList);		
	}
	
	public function matakuliaholahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iMatakuliah = $_REQUEST['id'];
		
		$this->view->detailMatakuliah = $this->matakuliah_serv->detailMatakuliahById($iMatakuliah);
	}
	
	public function matakuliahAction()
	{
		$id			= $_POST['id'];       
		$n_matakuliah		= $_POST['n_matakuliah'];       
		$e_ket			= $_POST['e_ket'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("id"  		=>$id,
							"n_matakuliah"  		=>$n_matakuliah,
							"e_ket"  		=>$e_ket	
							);
		
		$this->view->matakuliahInsert = $this->matakuliah_serv->matakuliahInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data matakuliah", $n_matakuliah." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Matakuliah";
		$this->view->hasil = $this->view->matakuliahInsert;
		
		$this->matakuliahlistAction();
		$this->render('matakuliahlist');
	}
	
	public function matakuliahupdateAction()
	{
		$id			= $_POST['id'];       
		$n_matakuliah		= $_POST['n_matakuliah'];       
		$e_ket			= $_POST['e_ket'];      
		$i_entry 		= $this->matakuliah;
		
		$dataMasukan = array("id"  		=>$id,
							"n_matakuliah"		=>$n_matakuliah,
							"e_ket"  		=>$e_ket	
							);
		
		$this->view->matakuliahUpdate = $this->matakuliah_serv->matakuliahUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data matakuliah", $n_matakuliah." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Mata kuliah";
		$this->view->hasil = $this->view->matakuliahUpdate;
		
		$this->matakuliahlistAction();
		$this->render('matakuliahlist');
	}
	
	public function matakuliahhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->matakuliahUpdate = $this->matakuliah_serv->matakuliahHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data matakuliah", $n_matakuliah." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Matakuliah";
		$this->view->hasil = $this->view->matakuliahUpdate;
		
		$this->matakuliahlistAction();
		$this->render('matakuliahlist');
	}
	

}
?>
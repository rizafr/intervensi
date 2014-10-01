<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admpropinsi_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_PropinsiController extends Zend_Controller_Action {
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
	   // $ssopropinsi = new Zend_Session_Namespace('ssopropinsi');
	   //echo "TEST ".$ssopropinsi->n_propinsi_grp." ".$ssopropinsi->i_propinsi." ".$ssopropinsi->i_peg_level_position;
	   $this->propinsi  = 'cdr';
	   
		$this->propinsi_serv = Adm_Admpropinsi_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssopropinsi = new Zend_Session_Namespace('ssopropinsi');
	    $this->iduser =$ssopropinsi->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function propinsijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('propinsijs');
    }
	
	//test OPen report
	//----------------------
	public function propinsilistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
			
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
		$kategoriCari 	= 'n_propinsi';
		$sortBy			= 'n_propinsi';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $this->view->katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totPropinsiList = $this->propinsi_serv->cariPropinsiList($dataMasukan,0,0,0);
		$this->view->propinsiList = $this->propinsi_serv->cariPropinsiList($dataMasukan,$currentPage, $numToDisplay,$this->view->totPropinsiList);		
	}
	
	public function propinsiolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iPropinsi = $_REQUEST['id'];
		
		$this->view->detailPropinsi = $this->propinsi_serv->detailPropinsiById($iPropinsi);
	}
	
	public function propinsiAction()
	{
		$id		= $_POST['id'];       
		$n_propinsi		= $_POST['n_propinsi'];      
		$c_propinsi		= $_POST['c_propinsi'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("n_propinsi"  	=>$n_propinsi,
							 "c_propinsi"  	=>$c_propinsi);
		//var_dump($dataMasukan);
		$this->view->propinsiInsert = $this->propinsi_serv->propinsiInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data propinsi", $n_propinsi." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Propinsi";
		$this->view->hasil = $this->view->propinsiInsert;
		$this->propinsilistAction();
		$this->render('propinsilist');
	}
	
	public function propinsiupdateAction()
	{	$id		= $_POST['id'];       
		$n_propinsi		= $_POST['n_propinsi'];      
		$c_propinsi		= $_POST['c_propinsi'];      
		$i_entry 		= $this->propinsi;
		
		
		$dataMasukan = array("id"  	=>$id,
							"n_propinsi"  	=>$n_propinsi,
							 "c_propinsi"  	=>$c_propinsi);
		
		$this->view->propinsiUpdate = $this->propinsi_serv->propinsiUpdate($dataMasukan);
		//var_dump($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data propinsi", $n_propinsi." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Propinsi";
		$this->view->hasil = $this->view->propinsiUpdate;
		
		$this->propinsilistAction();
		$this->render('propinsilist');
	}
	
	public function propinsihapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->propinsiUpdate = $this->propinsi_serv->propinsiHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data propinsi", $n_propinsi." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Propinsi";
		$this->view->hasil = $this->view->propinsiUpdate;
		
		$this->propinsilistAction();
		$this->render('propinsilist');
	}
	

}
?>
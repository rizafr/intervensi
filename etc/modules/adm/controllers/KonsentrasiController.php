<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admkonsentrasi_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "service/adm/Adm_Admprodi_Service.php";

class Adm_KonsentrasiController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->konsentrasi  = 'cdr';
		$this->prodi_serv = Adm_Admprodi_Service::getInstance();
		$this->konsentrasi_serv = Adm_Admkonsentrasi_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssokonsentrasi = new Zend_Session_Namespace('ssokonsentrasi');
	    $this->iduser =$ssokonsentrasi->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function konsentrasijsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('konsentrasijs');
    }
	
	//test OPen report
	//----------------------
	public function konsentrasilistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_konsentrasi';
		$sortBy			= 'n_konsentrasi';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKonsentrasiList = $this->konsentrasi_serv->cariKonsentrasiList($dataMasukan,0,0);
		$this->view->konsentrasiList = $this->konsentrasi_serv->cariKonsentrasiList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function konsentrasiolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iKonsentrasi = $_REQUEST['id'];
		$this->view->prodiList = $this->prodi_serv->getProdiListAll();
		$this->view->detailKonsentrasi = $this->konsentrasi_serv->detailKonsentrasiById($iKonsentrasi);
	}
	
	public function konsentrasiAction()
	{
		$id			= $_POST['id'];       
		$c_konsentrasi			= $_POST['c_konsentrasi'];      
		$n_konsentrasi		= $_POST['n_konsentrasi'];       
		$c_prodi			= $_POST['c_prodi'];      
		//$i_entry 		= $this->user;      

		$dataMasukan = array("id"  		=>$id,
							"c_konsentrasi"  		=>$c_konsentrasi,
							"n_konsentrasi"  		=>$n_konsentrasi,
							"c_prodi"  		=>$c_prodi	
							);
		
		$this->view->konsentrasiInsert = $this->konsentrasi_serv->konsentrasiInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data konsentrasi", $n_konsentrasi." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Konsentrasi";
		$this->view->hasil = $this->view->konsentrasiInsert;
		
		$this->konsentrasilistAction();
		$this->render('konsentrasilist');
	}
	
	public function konsentrasiupdateAction()
	{
		$id			= $_POST['id'];       
		$c_konsentrasi			= $_POST['c_konsentrasi'];      
		$n_konsentrasi		= $_POST['n_konsentrasi'];       
		$c_prodi			= $_POST['c_prodi'];      
		$i_entry 		= $this->konsentrasi;
		
		$dataMasukan = array("id"  		=>$id,
							"c_konsentrasi"  		=>$c_konsentrasi,
							"n_konsentrasi"		=>$n_konsentrasi,
							"c_prodi"  		=>$c_prodi	
							);
		
		$this->view->konsentrasiUpdate = $this->konsentrasi_serv->konsentrasiUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data konsentrasi", $n_konsentrasi." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Konsentrasi";
		$this->view->hasil = $this->view->konsentrasiUpdate;
		
		$this->konsentrasilistAction();
		$this->render('konsentrasilist');
	}
	
	public function konsentrasihapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->konsentrasiUpdate = $this->konsentrasi_serv->konsentrasiHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data konsentrasi", $n_konsentrasi." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Konsentrasi";
		$this->view->hasil = $this->view->konsentrasiUpdate;
		
		$this->konsentrasilistAction();
		$this->render('konsentrasilist');
	}
	

}
?>
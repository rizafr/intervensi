<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admkelas_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";
require_once "service/adm/Adm_Admprodi_Service.php";
require_once "service/ref/Ref_Refkonsentrasi_Service.php";

class Adm_KelasController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->kelas  = 'cdr';
		$this->prodi_serv = Adm_Admprodi_Service::getInstance();
		$this->kelas_serv = Adm_Admkelas_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $this->ref_serv = Ref_Refkonsentrasi_Service::getInstance();
		$ssokelas = new Zend_Session_Namespace('ssokelas');
	    $this->iduser =$ssokelas->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function kelasjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('kelasjs');
    }
	
	//test OPen report
	//----------------------
	public function kelaslistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 

		$this->view->prodiList = $this->prodi_serv->getProdiListAll();
		
		if ( $_REQUEST['param1']){$this->view->prodi= $_REQUEST['param1'];}
		else { $this->view->prodi= $_REQUEST['prodi'];
		}

		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_kelas';
		$sortBy			= 'n_kelas';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"prodi"			=> $this->view->prodi,
							"sortBy" => $sortBy,
							"sort" => $sort);
		$numToDisplay = 30;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totKelasList = $this->kelas_serv->cariKelasList($dataMasukan,0,0,0);
		$this->view->kelasList = $this->kelas_serv->cariKelasList($dataMasukan,$currentPage, $numToDisplay,$this->view->totKelasList);		
	}
	
	public function kelasolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$this->view->prodi= $_REQUEST['prodi'];
		$iKelas = $_REQUEST['id'];

		$this->view->konsentrasiList = $this->ref_serv->getKonsentrasiListAll(trim($this->view->prodi));
		$this->view->prodiList = $this->prodi_serv->getProdiListAll();
		$this->view->detailKelas = $this->kelas_serv->detailKelasById($iKelas);
	}
	
	public function kelasAction()
	{
		$id			= $_POST['id'];       
		$c_kelas			= $_POST['c_kelas'];      
		$n_kelas		= $_POST['n_kelas'];       
		$c_prodi			= $_POST['c_prodi'];   
		$c_konsentrasi			= $_POST['c_konsentrasi'];   
		$this->view->prodi= $_REQUEST['prodi'];
		//$i_entry 		= $this->user;      

		$dataMasukan = array(//"id"  		=>$id,
							"c_kelas"  		=>$c_kelas,
							"c_konsentrasi"  		=>$c_konsentrasi,
							"n_kelas"  		=>$n_kelas,
							"n_tingkat"  		=>$n_tingkat,
							"c_prodi"  		=>$c_prodi	
							);
		
		$this->view->kelasInsert = $this->kelas_serv->kelasInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data kelas", $n_kelas." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Kelas";
		$this->view->hasil = $this->view->kelasInsert;
		
		$this->kelaslistAction();
		$this->render('kelaslist');
	}
	
	public function kelasupdateAction()
	{
		$id					= $_POST['id'];   
		$this->view->prodi= $_REQUEST['prodi'];
		$c_kelas			= $_POST['c_kelas'];      
		$n_kelas			= $_POST['n_kelas'];       
		$n_tingkat			= $_POST['n_tingkat'];       
		$c_prodi			= $_POST['c_prodi'];      
		$c_konsentrasi			= $_POST['c_konsentrasi'];   
	//	$i_entry 		= $this->kelas;
		
		$dataMasukan = array("id"  			=>$id,
							"c_kelas"  		=>$c_kelas,
							"n_kelas"		=>$n_kelas,
							"c_konsentrasi"  		=>$c_konsentrasi,
							"n_tingkat"  	=>$n_tingkat,
							"c_prodi"  		=>$c_prodi	
							);
		
		$this->view->kelasUpdate = $this->kelas_serv->kelasUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data kelas", $n_kelas." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Kelas";
		$this->view->hasil = $this->view->kelasUpdate;
		
		$this->kelaslistAction();
		$this->render('kelaslist');
	}
	
	public function kelashapusAction()
	{
		$id 		= $_REQUEST['id'];
		$this->view->prodi= $_REQUEST['prodi'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->kelasUpdate = $this->kelas_serv->kelasHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data kelas", $n_kelas." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Kelas";
		$this->view->hasil = $this->view->kelasUpdate;
		
		$this->kelaslistAction();
		$this->render('kelaslist');
	}
	

}
?>
<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admmenjabat_Service.php";
require_once "service/adm/Adm_Admuser_Service.php";
require_once "service/adm/Adm_Admjabatan_Service.php";
require_once "service/adm/Adm_AdmstrukturOrg_Service.php";

class Adm_MenjabatController extends Zend_Controller_Action {
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
	   // $ssogroup = new Zend_Session_Namespace('ssogroup');
	   //echo "TEST ".$ssogroup->n_user_grp." ".$ssogroup->i_user." ".$ssogroup->i_peg_nip;
	   $this->user  = 'cdr';
	   // $this->username  = 'Yuliah';
	   //$this->nip  = strtoupper($this->id['nip']);
	   // $this->usernip  = '060046350';
	   // $this->kdorg = 'SK1201';
	 // $this->modul    = '5';
	 // $this->category = 'A';
		
		$this->menjabat_serv = Adm_Admmenjabat_Service::getInstance();
		$this->user_serv = Adm_Admuser_Service::getInstance();
		$this->jabatan_serv = Adm_Admjabatan_Service::getInstance();
		$this->org_serv = Adm_AdmstrukturOrg_Service::getInstance();
		
		// $this->sdm_caripeg_serv = Sdm_Caripegawai_Service::getInstance();
    }
	
    public function indexAction() {
	   
    }
	
	public function menjabatjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('menjabatjs');
    }
	
	//test OPen report
	//----------------------
	public function menjabatlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'nip';
		$sortBy			= 'nip';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totMenjabatList = $this->menjabat_serv->cariMenjabatList($dataMasukan,0,0);
		$this->view->menjabatList = $this->menjabat_serv->cariMenjabatList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function menjabatolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$id_menjabat = $_REQUEST['id_menjabat'];
		//echo "iuser --->".$iUser;
		$this->view->detailMenjabat = $this->menjabat_serv->detailMenjabatById($id_menjabat);
		$this->view->userList = $this->user_serv->getUserListAll();
		$this->view->jabatanList = $this->jabatan_serv->getJabatanListAll();
		$this->view->orgList = $this->org_serv->getStrukturOrgListAll();
		
	}
	
	public function menjabatAction()
	{
		$nip					= $_POST['nip'];      
		$kd_jabatan				= $_POST['kd_jabatan'];      
		$kd_struktur_org		= $_POST['kd_struktur_org'];
		$kd_jabatan_induk		= $_POST['kd_jabatan_induk'];       
		$tgl_mulai = null;
		$tglMulai	 	= $_POST['tglMulai'];
		$blnMulai	 	= $_POST['blnMulai'];
		$thnMulai	 	= $_POST['thnMulai'];
		if($thnMulai != '#'){ 
			if($blnMulai != '#') {
				if($tglMulai) {
					$tgl_mulai = $thnMulai.'-'.$blnMulai.'-'.$tglMulai;
				}
			}
		}

		$tgl_selesai = null;      
		$tglSelesai	 	= $_POST['tglSelesai'];
		$blnSelesai	 	= $_POST['blnSelesai'];
		$thnSelesai	 	= $_POST['thnSelesai'];
		if($thnSelesai != '#'){ 
			if($blnSelesai != '#') {
				if($tglSelesai) {
					$tgl_selesai = $thnSelesai.'-'.$blnSelesai.'-'.$tglSelesai;
				}
			}
		}
		
		$dataMasukan = array("nip"				=>$nip,
							"kd_jabatan"	 	=>$kd_jabatan,
							"kd_struktur_org"  	=>$kd_struktur_org,
							"kd_jabatan_induk"  =>$kd_jabatan_induk,
							"tgl_mulai"  		=>$tgl_mulai,
							"tgl_selesai"		=>$tgl_selesai);
		
		$this->view->menjabatInsert = $this->menjabat_serv->menjabatInsert($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "Menjabat";
		$this->view->hasil = $hasil;
		
		$this->menjabatlistAction();
		$this->render('menjabatlist');
	}
	
	public function menjabatupdateAction()
	{
		$id_menjabat		= $_POST['id_menjabat'];       
		$nip				= $_POST['nip'];      
		$kd_jabatan			= $_POST['kd_jabatan'];      
		$kd_struktur_org	= $_POST['kd_struktur_org'];
		$kd_jabatan_induk	= $_POST['kd_jabatan_induk'];       
		$tgl_mulai = null;
		$tglMulai	 	= $_POST['tglMulai'];
		$blnMulai	 	= $_POST['blnMulai'];
		$thnMulai	 	= $_POST['thnMulai'];
		if($thnMulai != '#'){ 
			if($blnMulai != '#') {
				if($tglMulai) {
					$tgl_mulai = $thnMulai.'-'.$blnMulai.'-'.$tglMulai;
				}
			}
		}

		$tgl_selesai = null;      
		$tglSelesai	 	= $_POST['tglSelesai'];
		$blnSelesai	 	= $_POST['blnSelesai'];
		$thnSelesai	 	= $_POST['thnSelesai'];
		if($thnSelesai != '#'){ 
			if($blnSelesai != '#') {
				if($tglSelesai) {
					$tgl_selesai = $thnSelesai.'-'.$blnSelesai.'-'.$tglSelesai;
				}
			}
		}
		$c_statusdelete		= $_POST['c_statusdelete'];           
		$i_entry			= $_POST['i_entry'];          
		$d_entry			= $_POST['d_entry'];   
		
		$dataMasukan = array("id_menjabat"  	=>$id_menjabat,
							"nip"				=>$nip,
							"kd_jabatan"	 	=>$kd_jabatan,
							"kd_struktur_org"  	=>$kd_struktur_org,
							"kd_jabatan_induk"  =>$kd_jabatan_induk,
							"tgl_mulai"  		=>$tgl_mulai,
							"tgl_selesai"		=>$tgl_selesai);
		
		$this->view->menjabatUpdate = $this->menjabat_serv->menjabatUpdate($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "Menjabat";
		$this->view->hasil = $hasil;
		
		$this->menjabatlistAction();
		$this->render('menjabatlist');
	}
	
	public function menjabathapusAction()
	{
		$id_menjabat 		= $_REQUEST['id_menjabat'];
		
		$dataMasukan = array("id_menjabat" => $id_menjabat);
		
		$this->view->menjabatUpdate = $this->menjabat_serv->menjabatHapus($dataMasukan);
		$this->view->proses = "3";	
		$this->view->keterangan = "Menjabat";
		$this->view->hasil = $hasil;
		
		$this->menjabatlistAction();
		$this->render('menjabatlist');
	}
	

}
?>
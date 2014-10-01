<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_AdmstrukturOrg_Service.php";

class Adm_StrukturOrgController extends Zend_Controller_Action {
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
		
		$this->strukturOrg_serv = Adm_AdmstrukturOrg_Service::getInstance();
		
		// $this->sdm_caripeg_serv = Sdm_Caripegawai_Service::getInstance();
    }
	
    public function indexAction() {
	   
    }
	
	public function strukturOrgjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('strukturOrgjs');
    }
	
	//test OPen report
	//----------------------
	public function strukturOrglistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'nm_level';
		$sortBy			= 'kd_struktur_org_induk';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totStrukturOrgList = $this->strukturOrg_serv->cariStrukturOrgList($dataMasukan,0,0);
		$this->view->strukturOrgList = $this->strukturOrg_serv->cariStrukturOrgList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function strukturOrgolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$kd_struktur_org = $_REQUEST['kd_struktur_org'];
		//echo "iuser --->".$iUser;
		$this->view->detailStrukturOrg = $this->strukturOrg_serv->detailStrukturOrgById($kd_struktur_org);
	}
	
	public function strukturOrgAction()
	{
		$kd_struktur_org		= $_POST['kd_struktur_org'];       
		$kd_struktur_org_induk	= $_POST['kd_struktur_org_induk'];      
		$level					= $_POST['level'];      
		$nm_level				= $_POST['nm_level'];      
		
		
		
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
		$c_statusdelete			= $_POST['c_statusdelete'];           
		$i_entry				= $_POST['i_entry'];          
		$d_entry				= $_POST['d_entry'];   

		$dataMasukan = array("kd_struktur_org"				=>$kd_struktur_org,
							"kd_struktur_org_induk"			=>$kd_struktur_org_induk,
							"level"							=>$level,
							"nm_level" 						=>$nm_level,
							"tgl_mulai"						=>$tgl_mulai,
							"tgl_selesai"					=>$tgl_selesai);
		
		$this->view->strukturOrgInsert = $this->strukturOrg_serv->strukturOrgInsert($dataMasukan);
		$this->view->proses = "1";	
		$this->view->keterangan = "StrukturOrg";
		$this->view->hasil = $hasil;
		
		$this->strukturOrglistAction();
		$this->render('strukturOrglist');
	}
	
	public function strukturOrgupdateAction()
	{
		$kd_struktur_org		= $_POST['kd_struktur_org'];       
		$kd_struktur_org_induk	= $_POST['kd_struktur_org_induk'];      
		$level					= $_POST['level'];      
		$nm_level				= $_POST['nm_level'];      
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
		$c_statusdelete			= $_POST['c_statusdelete'];           
		$i_entry				= $_POST['i_entry'];          
		$d_entry				= $_POST['d_entry'];   
		
		$dataMasukan = array("kd_struktur_org"  	=>$kd_struktur_org,
							"kd_struktur_org_induk"	=>$kd_struktur_org_induk,
							"level"					=>$level,
							"nm_level" 				=>$nm_level,
							"tgl_mulai"				=>$tgl_mulai,
							"tgl_selesai"			=>$tgl_selesai);
		
		$this->view->strukturOrgUpdate = $this->strukturOrg_serv->strukturOrgUpdate($dataMasukan);
		$this->view->proses = "2";	
		$this->view->keterangan = "StrukturOrg";
		$this->view->hasil = $hasil;
		
		$this->strukturOrglistAction();
		$this->render('strukturOrglist');
	}
	
	public function strukturOrghapusAction()
	{
		$kd_struktur_org 		= $_REQUEST['kd_struktur_org'];
		
		$dataMasukan = array("kd_struktur_org" => $kd_struktur_org);
		
		$this->view->strukturOrgUpdate = $this->strukturOrg_serv->strukturOrgHapus($dataMasukan);
		$this->view->proses = "3";	
		$this->view->keterangan = "StrukturOrg";
		$this->view->hasil = $hasil;
		
		$this->strukturOrglistAction();
		$this->render('strukturOrglist');
	}
	

}
?>
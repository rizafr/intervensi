<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once "service/adm/Adm_Admjurusan_Service.php";
require_once "service/adm/logfile.php";
require_once "service/sso/Sso_User_Service.php";

class Adm_JurusanController extends Zend_Controller_Action {
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
	   // $ssojurusan = new Zend_Session_Namespace('ssojurusan');
	   //echo "TEST ".$ssojurusan->n_jurE_grp." ".$ssojurusan->i_jurusan." ".$ssojurusan->i_peg_level_position;
	   $this->jurusan  = 'cdr';
	   
		$this->jurusan_serv = Adm_Admjurusan_Service::getInstance();
		$this->sso_serv = Sso_User_Service::getInstance();
	    $ssojurusan = new Zend_Session_Namespace('ssojurusan');
	    $this->iduser =$ssojurusan->user_id;
	    $this->view->namauser = $this->sso_serv->getDataUserNama($this->iduser);
		$this->Logfile = new logfile;
		
    }
	
    public function indexAction() {
	   
    }
	
	public function jurusanjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('jurusanjs');
    }
	
	//test OPen report
	//----------------------
	public function jurusanlistAction()
	{
		$currentPage = $_REQUEST['currentPage']; 
		//echo $currentPage;
 			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		
		$katakunciCari 	= $_POST['carii'];
		$kategoriCari 	= 'n_jur';
		$sortBy			= 'n_jur';
		$sort			= 'asc';
		
		$dataMasukan = array("kategoriCari" => $kategoriCari,
							"katakunciCari" => $katakunciCari,
							"sortBy" => $sortBy,
							"sort" => $sort);
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totJurusanList = $this->jurusan_serv->cariJurusanList($dataMasukan,0,0);
		$this->view->jurusanList = $this->jurusan_serv->cariJurusanList($dataMasukan,$currentPage, $numToDisplay);		
	}
	
	public function jurusanolahdataAction()
	{
		$this->view->jenisForm = $_REQUEST['jenisForm'];
		$iJurusan = $_REQUEST['id'];
		
		$this->view->detailJurusan = $this->jurusan_serv->detailJurusanById($iJurusan);
	}
	
	public function jurusanAction()
	{
		$c_jur			= $_POST['c_jur'];      
		$n_jur		= $_POST['n_jur'];       
		$c_jurdikti			= $_POST['c_jurdikti'];      
		$c_kbk		= $_POST['c_kbk'];       
		$n_singkatan			= $_POST['n_singkatan'];      
		$i_nosk		= $_POST['i_nosk'];       
		$d_tglsk			= $_POST['d_tglsk'];      
		$n_akreditasi		= $_POST['n_akreditasi'];       
		$i_noakreditasi			= $_POST['i_noakreditasi'];      
		$d_tglakreditasi		= $_POST['d_tglakreditasi'];       
		$c_jenjang			= $_POST['c_jenjang'];      
		$e_ket			= $_POST['e_ket'];      
		//$i_entry 		= $this->user;      
if($d_tglsk) {
		$bln = substr($d_tglsk, 3, 2);$tgl = substr($d_tglsk, 0, 2);$thn = substr($d_tglsk, 6, 4);
		$d_tglsk = $bln."/".$tgl."/".$thn;
		} else {$d_tglsk ="";}
		if($d_tglakreditasi) {
		$bln = substr($d_tglakreditasi, 3, 2);$tgl = substr($d_tglakreditasi, 0, 2);$thn = substr($d_tglakreditasi, 6, 4);
		$d_tglakreditasi = $bln."/".$tgl."/".$thn;
		} else {$d_tglakreditasi ="";}
		$dataMasukan = array(   "c_jur"  			=>$c_jur,
								"n_jur"			=>$n_jur,
								"c_jurdikti"  				=>$c_jurdikti,
								"c_kbk"  			=>$c_kbk,
								"n_singkatan"			=>$n_singkatan,
								"i_nosk"  				=>$i_nosk,
								"d_tglsk"  			=>$d_tglsk,
								"n_akreditasi"			=>$n_akreditasi,
								"i_noakreditasi"  				=>$i_noakreditasi,
								"d_tglakreditasi"  			=>$d_tglakreditasi,
								"c_jenjang"			=>$c_jenjang,
								"e_ket"  			=>$e_ket
							);
		
		$this->view->jurusanInsert = $this->jurusan_serv->jurusanInsert($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Insert data jurusan", $n_jur." (".$id.")");
		$this->view->proses = "1";	
		$this->view->keterangan = "Jurusan";
		$this->view->hasil = $this->view->jurusanInsert;
		
		$this->jurusanlistAction();
		$this->render('jurusanlist');
	}
	
	public function jurusanupdateAction()
	{
		$id			= $_POST['id'];       
		$c_jur			= $_POST['c_jur'];      
		$n_jur		= $_POST['n_jur'];       
		$c_jurdikti			= $_POST['c_jurdikti'];      
		$c_kbk		= $_POST['c_kbk'];       
		$n_singkatan			= $_POST['n_singkatan'];      
		$i_nosk		= $_POST['i_nosk'];       
		$d_tglsk			= $_POST['d_tglsk'];      
		$n_akreditasi		= $_POST['n_akreditasi'];       
		$i_noakreditasi			= $_POST['i_noakreditasi'];      
		$d_tglakreditasi		= $_POST['d_tglakreditasi'];       
		$c_jenjang			= $_POST['c_jenjang'];      
		$e_ket			= $_POST['e_ket'];      
		if($d_tglsk) {
		$bln = substr($d_tglsk, 3, 2);$tgl = substr($d_tglsk, 0, 2);$thn = substr($d_tglsk, 6, 4);
		$d_tglsk = $bln."/".$tgl."/".$thn;
		} else {$d_tglsk ="";}
		if($d_tglakreditasi) {
		$bln = substr($d_tglakreditasi, 3, 2);$tgl = substr($d_tglakreditasi, 0, 2);$thn = substr($d_tglakreditasi, 6, 4);
		$d_tglakreditasi = $bln."/".$tgl."/".$thn;
		} else {$d_tglakreditasi ="";}
		$dataMasukan = array(   "id"  				=>$id,
								"c_jur"  			=>$c_jur,
								"n_jur"			=>$n_jur,
								"c_jurdikti"  				=>$c_jurdikti,
								"c_kbk"  			=>$c_kbk,
								"n_singkatan"			=>$n_singkatan,
								"i_nosk"  				=>$i_nosk,
								"d_tglsk"  			=>$d_tglsk,
								"n_akreditasi"			=>$n_akreditasi,
								"i_noakreditasi"  				=>$i_noakreditasi,
								"d_tglakreditasi"  			=>$d_tglakreditasi,
								"c_jenjang"			=>$c_jenjang,
								"e_ket"  			=>$e_ket	
							);
		
		$this->view->jurusanUpdate = $this->jurusan_serv->jurusanUpdate($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Ubah data jurusan", $n_jur." (".$id.")");
		$this->view->proses = "2";	
		$this->view->keterangan = "Jurusan";
		$this->view->hasil = $this->view->jurusanUpdate;
		
		$this->jurusanlistAction();
		$this->render('jurusanlist');
	}
	
	public function jurusanhapusAction()
	{
		$id 		= $_REQUEST['id'];
		
		$dataMasukan = array("id" => $id);
		
		$this->view->jurusanUpdate = $this->jurusan_serv->jurusanHapus($dataMasukan);
		$this->Logfile->createLog($this->view->namauser, "Hapus data jurusan", $n_jur." (".$id.")");
		$this->view->proses = "3";	
		$this->view->keterangan = "Jurusan";
		$this->view->hasil = $this->view->jurusanUpdate;
		
		$this->jurusanlistAction();
		$this->render('jurusanlist');
	}
	

}
?>
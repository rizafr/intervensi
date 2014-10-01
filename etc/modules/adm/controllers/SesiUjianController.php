<?php
require_once 'Zend/Controller/Action.php';
require_once "service/adm/adm_admsesiujian_Service.php";

class Adm_SesiUjianController extends Zend_Controller_Action {
	private $serv_ref;
	private $userlog;
	private $userid;	
	
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');
		$this->datasesi_serv = adm_admsesiujian_Service::getInstance();
		$ssogroup = new Zend_Session_Namespace('ssogroup');
		$this->userid =$ssogroup->i_user;
		$userid =$this->userid;
		
		$ssogroupnip = new Zend_Session_Namespace('ssogroupnip');
		$this->nip_user =$ssogroupnip->nip_user;
		
    }
	
    public function indexAction() {

    }

    public function sesiujianAction() {
	}	
    public function sesiujianlistAction() {
		$currentPage = $_REQUEST['currentPage'];
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		$cari=" and c_statusdelete='N'  ";
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totsesi = $this->datasesi_serv->getTrSesi($cari,0,0,0);
		$this->view->sesiList = $this->datasesi_serv->getTrSesi($cari,$currentPage, $numToDisplay, $this->view->totsesi);		
    }
	
    public function sesiubahAction() {
		$id = $_GET['id'];		
		$cari=" and id='$id'  ";	
		$this->view->datasesi = $this->datasesi_serv->getTrSesi($cari,1, 1,1);	
		$this->_helper->viewRenderer('sesiujian');	
		$this->view->par ='ubah';
    }	
    public function sesiujianjsAction() {
	   header('content-type : text/javascript');
	   $this->render('sesiujianjs');
    }
    public function maintaindataAction() {
		$perintah=$_POST['perintah'];
		$n_sesi=$_POST['sesiawal']." - ".$_POST['sesiakhir'];
		$c_statusdelete='N';
				$MaintainData = array(	"id"=>$_POST['id'],
										"c_sesi"=>$_POST['c_sesi'],
										"n_sesi"=>$n_sesi,
										"c_statusdelete"=>$c_statusdelete,
										"i_entry"=>$userid);
				if ($perintah=='Simpan'){
				$parpesan="Simpan Data";
					$hasil = $this->datasesi_serv->tambahDataSesi($MaintainData);
				}
				else if($perintah=='Ubah'){
				$parpesan="Ubah Data";
					$hasil = $this->datasesi_serv->ubahDataSesi($MaintainData);
				}
		$cari=" and c_statusdelete='N'   ";		
		$currentPage = $_REQUEST['currentPage'];
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		$cari=" and c_statusdelete='N'   ";
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totsesi = $this->datasesi_serv->getTrSesi($cari,0,0);
		$this->view->sesiList = $this->datasesi_serv->getTrSesi($cari,$currentPage, $numToDisplay);		
		
		$pesan=$parpesan." ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$userid =$this->nip_user;	
		$this->view->userid =$userid;
		
		$this->_helper->viewRenderer('sesiujianlist');		
		
	 }
	

     public function hapusdatasesiAction() {

		$c_statusdelete='Y';
				$MaintainData = array(	"id"=>$_GET['id'],
										"c_sesi"=>$_GET['c_sesi'],
										"c_statusdelete"=>$c_statusdelete,
										"i_entry"=>$userid);
		$hasil = $this->datasesi_serv->hapusDataSesi($MaintainData);
		$parpesan="Hapus Data";
		$pesan=$parpesan." ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$userid =$this->nip_user;	
		$this->view->userid =$userid;
		$cari=" and c_statusdelete='N'   ";		
		$currentPage = $_REQUEST['currentPage'];

	?>
	<script>
		location.href="#top";
		doCounter(5);
	</script>	
	<?			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		$cari=" and c_statusdelete='N'   ";
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totsesi = $this->datasesi_serv->getTrSesi($cari,0,0,0);
		$this->view->sesiList = $this->datasesi_serv->getTrSesi($cari,$currentPage, $numToDisplay,$this->view->totsesi);				
		$this->_helper->viewRenderer('sesiujianlist');	
	}
	

	
}

?>
<?php
require_once 'Zend/Controller/Action.php';
require_once "service/adm/adm_admruang_Service.php";

class Adm_RuangController extends Zend_Controller_Action {
	private $serv_ref;
	private $userlog;
	private $userid;	
	
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');
		$this->dataruang_serv = adm_admruang_Service::getInstance();
		$ssogroup = new Zend_Session_Namespace('ssogroup');
		$this->userid =$ssogroup->i_user;
		$userid =$this->userid;
		
		$ssogroupnip = new Zend_Session_Namespace('ssogroupnip');
		$this->nip_user =$ssogroupnip->nip_user;
		
    }
	
    public function indexAction() {

    }

    public function ruangAction() {
	}	
    public function ruanglistAction() {
		$currentPage = $_REQUEST['currentPage'];
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		$cari=" and c_statusdelete='N'";
		$numToDisplay = 40;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totsesi = $this->dataruang_serv->getTrRuang($cari,0,0,0);
		$this->view->sesiList = $this->dataruang_serv->getTrRuang($cari,$currentPage, $numToDisplay,$this->view->totsesi);		
    }
	
    public function ruangubahAction() {
		$id = $_GET['id'];		
		$cari=" and id='$id'";	
		$this->view->datasesi = $this->dataruang_serv->getTrRuang($cari,1, 1,1);	
		$this->_helper->viewRenderer('ruang');	
		$this->view->par ='ubah';
    }	
    public function ruangjsAction() {
	   header('content-type : text/javascript');
	   $this->render('ruangjs');
    }
    public function maintaindataAction() {
		$perintah=$_POST['perintah'];
		$c_statusdelete='N';
				$MaintainData = array(	"id"=>$_POST['id'],
										"n_ruang"=>$_POST['n_ruang'],
										"n_ket"=>$_POST['n_ket'],
										"c_statusdelete"=>$c_statusdelete,
										"i_entry"=>$userid);
				if ($perintah=='Simpan'){
				$parpesan="Simpan Data";
					$hasil = $this->dataruang_serv->tambahDataRuang($MaintainData);
				}
				else if($perintah=='Ubah'){
				$parpesan="Ubah Data";
					$hasil = $this->dataruang_serv->ubahDataRuang($MaintainData);
				}
		$cari=" and c_statusdelete='N'";		
		$currentPage = $_REQUEST['currentPage'];
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		$cari=" and c_statusdelete='N'";
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totsesi = $this->dataruang_serv->getTrRuang($cari,0,0,0);
		$this->view->sesiList = $this->dataruang_serv->getTrRuang($cari,$currentPage, $numToDisplay,$this->view->totsesi);		
		
		$pesan=$parpesan." ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$userid =$this->nip_user;	
		$this->view->userid =$userid;
		
		$this->_helper->viewRenderer('ruanglist');		
		
	 }
	

     public function hapusdataruangAction() {

		$c_statusdelete='Y';
				$MaintainData = array(	"id"=>$_GET['id'],
										"c_statusdelete"=>$c_statusdelete,
										"i_entry"=>$userid);
		$hasil = $this->dataruang_serv->hapusDataRuang($MaintainData);
		$parpesan="Hapus Data";
		$pesan=$parpesan." ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$userid =$this->nip_user;	
		$this->view->userid =$userid;
		$cari=" and c_statusdelete='N'";		
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
		$cari=" and c_statusdelete='N'";
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totsesi = $this->dataruang_serv->getTrRuang($cari,0,0,0);
		$this->view->sesiList = $this->dataruang_serv->getTrRuang($cari,$currentPage, $numToDisplay,$this->view->totsesi);				
		$this->_helper->viewRenderer('ruanglist');	
	}
	

	
}

?>
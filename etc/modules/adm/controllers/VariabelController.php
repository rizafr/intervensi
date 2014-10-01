<?php
require_once 'Zend/Controller/Action.php';
require_once "service/adm/Adm_Admvariabel_Service.php";
require_once "service/adm/Adm_Admthnajaran_Service.php";

class Adm_VariabelController extends Zend_Controller_Action {
	private $serv_ref;
	private $userlog;
	private $userid;	
	
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');
		$this->datavariabel_serv = adm_admvariabel_service::getInstance();
		$this->thnajaran_serv = adm_admthnajaran_service::getInstance();
		
		$ssogroup = new Zend_Session_Namespace('ssogroup');
		$this->userid =$ssogroup->i_user;
		$userid =$this->userid;
		
		$ssogroupnip = new Zend_Session_Namespace('ssogroupnip');
		$this->nip_user =$ssogroupnip->nip_user;
		
    }
	
    public function indexAction() {

    }

    public function variabelAction() {
	}	
    public function variabellistAction() {
		$currentPage = $_REQUEST['currentPage'];
			
		if((!$currentPage) || ($currentPage == 'undefined'))
		{
			$currentPage = 1;
		} 
		$cari=" and c_statusdelete='N'";
		$numToDisplay = 20;
		$this->view->numToDisplay = $numToDisplay;
		$this->view->currentPage = $currentPage;
		$this->view->totsesi = $this->datavariabel_serv->getTrVariabel($cari,0,0);
		$this->view->sesiList = $this->datavariabel_serv->getTrVariabel($cari,$currentPage, $numToDisplay);		
    }
	
    public function variabelubahAction() {
		$id = $_GET['id'];		
		$cari=" and id='$id'";	
		$this->view->datasesi = $this->datavariabel_serv->getTrVariabel($cari,1, 1);	
		$this->_helper->viewRenderer('variabel');	

	    $this->view->thnAjaranList = $this->thnajaran_serv->getthnAjaranListAll();


		$this->view->par ='ubah';
    }	
    public function variabeljsAction() {
	   header('content-type : text/javascript');
	   $this->render('variabeljs');
    }
    public function maintaindataAction() {
		$perintah=$_POST['perintah'];
		$c_statusdelete='N';
				$MaintainData = array(	"id"=>$_POST['id'],
										"c_var"=>$_POST['c_var'],
										"n_var"=>$_POST['n_var'],
										"c_statusdelete"=>$c_statusdelete,
										"i_entry"=>$userid);
				if ($perintah=='Simpan'){
				$parpesan="Simpan Data";
					$hasil = $this->datavariabel_serv->tambahDataVariabel($MaintainData);
				}
				else if($perintah=='Ubah'){
				$parpesan="Ubah Data";
					$hasil = $this->datavariabel_serv->ubahDataVariabel($MaintainData);
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
		$this->view->totsesi = $this->datavariabel_serv->getTrVariabel($cari,0,0);
		$this->view->sesiList = $this->datavariabel_serv->getTrVariabel($cari,$currentPage, $numToDisplay);		
		
		$pesan=$parpesan." ".$hasil;
		$this->view->pesan = $pesan;
		$this->view->pesancek = $hasil;
		$userid =$this->nip_user;	
		$this->view->userid =$userid;
		
		$this->_helper->viewRenderer('variabellist');		
		
	 }
	

     public function hapusdatavariabelAction() {

		$c_statusdelete='Y';
				$MaintainData = array(	"id"=>$_GET['id'],
										"c_sesi"=>$_GET['c_sesi'],
										"c_statusdelete"=>$c_statusdelete,
										"i_entry"=>$userid);
		$hasil = $this->datavariabel_serv->hapusDataVariabel($MaintainData);
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
		$this->view->totsesi = $this->datavariabel_serv->getTrVariabel($cari,0,0);
		$this->view->sesiList = $this->datavariabel_serv->getTrVariabel($cari,$currentPage, $numToDisplay);				
		$this->_helper->viewRenderer('variabellist');	
	}
	

	
}

?>
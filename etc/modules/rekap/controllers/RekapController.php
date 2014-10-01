<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
require_once "service/sso/Sso_User_Service.php";
require_once "service/rekap/Rekap_Service.php";
require_once "service/kegiatan/Kegiatan_Service.php";

class Rekap_RekapController extends Zend_Controller_Action {
	
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->auth = Zend_Auth::getInstance();	   
		$this->view->baseData = $registry->get('baseData');
		$this->view->basePath = $registry->get('basepath');
		$this->view->procPath = $registry->get('procpath');	 
		$this->sso_serv = Sso_User_Service::getInstance();
		$this->rekap_serv = Rekap_Service::getInstance();		
		$this->kegiatan_serv = Kegiatan_Service::getInstance();		
								   
		$ssouserpengguna = new Zend_Session_Namespace('ssouserpengguna');
		$ssouserpassword= new Zend_Session_Namespace('ssouserpassword');
		$ssouserKodeInstansi= new Zend_Session_Namespace('ssouserKodeInstansi');
		$ssouserlevel = new Zend_Session_Namespace('ssouserlevel');
		
		$this->pengguna =$ssouserpengguna->pengguna;			
		$this->password =$ssouserpassword->password;
		$this->KodeInstansi =$ssouserKodeInstansi->KodeInstansi;
		$this->level =$ssouserlevel->level;
    }
	
    public function indexAction() {
	
    }
	
	public function rekapmenukegiatanAction() {
		$this->view->rekapkegMenu = $this->rekap_serv->getrekapedit();
		$this->view->kegList = $this->rekap_serv->getKegListAll();
		
		$this->view->rekapkelMenu = $this->rekap_serv->getrekapkeledit();
		$this->view->kelList = $this->rekap_serv->getKelListAll();
    }
	
	public function rekapmenukelurahanAction() {		
		$this->view->rekapkelMenu = $this->rekap_serv->getrekapkeledit();
		$this->view->kelList = $this->rekap_serv->getKelListAll();
		
    }
	
	public function kontakAction() {		
		$this->view->kontak;
		
    }
	
	
	
	
	
}
?>
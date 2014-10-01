<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';

class Adm_LogFileController extends Zend_Controller_Action {
	private $auditor_serv;
	private $id;
	private $kdorg;
		
    public function init() {
		$registry = Zend_Registry::getInstance();
		$this->view->basePath = $registry->get('basepath'); 
		$this->basePath = $registry->get('basepath'); 
        $this->view->pathUPLD = $registry->get('pathUPLD');
        $this->view->procPath = $registry->get('procpath');
	    $this->user  = 'cdr';
	   
		
		
		// $this->sdm_caripeg_serv = Sdm_Caripegawai_Service::getInstance();
    }
	
    public function indexAction() {
	   
    }
	
/*	public function userjsAction() 
    {
	 header('content-type : text/javascript');
	 $this->render('userjs');
    }
	*/
	//test OPen report
	//----------------------
	public function logfilelistAction()
	{
		
	}
	public function gridlogfileAction()
	{
		
	}
	
	

}
?>
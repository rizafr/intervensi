<?php
require_once 'Zend/Controller/Action.php';

class IndexController extends Zend_Controller_Action {
    public function indexAction() {
	   //UNTUK SETTING GLOBAL BASE PATH
	   $registry = Zend_Registry::getInstance();
	   $this->view->basePath = $registry->get('basepath');

	   // indexAction default kepunyaan IndexController dalam modul default
	   $this->_forward('index','index','home',null);
    }
	
    public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, render the error template
            return $this->render('error');
        }
        // all other methods throw an exception
        throw new Exception('Invalid method "' . $method . '" called');
    }

    public function testAction() {
	     $this->_helper->viewRenderer->setNoRender(true);
	     echo "test Action ";
    }

    public function listAction() {
	     $this->_helper->viewRenderer->setNoRender(true);
	     echo "List  Action ";
    }
}
?>
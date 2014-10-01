<?php
require_once 'Zend/Controller/Action.php';
require_once "service/adm/adm_dds_service.php";
class Adm_ddsController extends Zend_Controller_Action {
    public function init() {
        // Local to this controller only; affects all actions, as loaded in init:
          $registry = Zend_Registry::getInstance();
	  $this->view->basePath = $registry->get('basepath'); 
	  $this->adm_listtabel_serv = adm_dds_service::getInstance();
	  $this->adm_listdata_serv = adm_dds_service::getInstance();
	  $this->adm_ntablesearch_serv = adm_dds_service::getInstance();
	  $this->adm_tabeldtl_serv = adm_dds_service::getInstance();
	  $this->adm_ndatasearch_serv = adm_dds_service::getInstance();
	  $this->adm_datadtl_serv = adm_dds_service::getInstance();
    }
	
    public function indexAction() {
	    echo "Dds Controller indexAction";
	// $registry = Zend_Registry::getInstance();
      //   $this->view->basePath = $registry->get('basepath'); 
    }
    
    public function listtabelAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('%');
    }   
    
    public function listtabelAdmAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('ADMINISTRASI SYSTEM');
    }  
    public function listtabelCSAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('CURRENT SYSTEM');
    }  
    public function listtabelAsetAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('ASET');
    }  
    public function listtabelKeuAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('KEUANGAN');
    }  
    public function listtabelPngAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('PENGADAAN');
    }  
    public function listtabelPrncAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('PERENCANAAN');
    }  
    public function listtabelSDMAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('SDM');
    }
    public function listtabelTAPAction() {
     //  echo "List tabel DDS"; 
        $this->view->tableList = $this->adm_listtabel_serv->getTableList('TATA PERSURATAN');
    }   
    
    public function listdataAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('%');
    }  
    public function listdataAdmAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('ADMINISTRASI SYSTEM');
    }  
    public function listdataAsetAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('ASET');
    }  
    public function listdataCSAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('CURRENT SYSTEM');
    }  
    public function listdataKeuAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('KEUANGAN');
    }  
    public function listdataPngAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('PENGADAAN');
    }  
    public function listdataPrncAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('PERENCANAAN');
    }  
    public function listdataSDMAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('SDM');
    } 
    public function listdataTAPAction() {
     //  echo "List tabel DDS"; 
        $this->view->dataList = $this->adm_listdata_serv->getDataList('TATA PERSURATAN');
    } 
    public function caritabelAction() {
       //echo "Cari tabel DDS"; 
     	$ntabel = $_POST['ntabel'];
	$etabel = $_POST['etabel'];
	$this->view->hsltabelList = $this->adm_ntablesearch_serv->ntablesearch($ntabel,$etabel);
    }
    public function tabelviewAction() {
	    $kode = $_REQUEST['kode'];
	//   echo "kode--> ".$kode.'<br>';
	   $this->view->lihat_tabdtl = $this->adm_tabeldtl_serv->getTabelBykode($kode);
	} 
     public function caridataAction() {
       //echo "Cari tabel DDS"; 
     	$nkolom = $_POST['nkolom'];
	$ekolom = $_POST['ekolom'];
	$this->view->hsldataList = $this->adm_ndatasearch_serv->ndatasearch($nkolom,$ekolom);
    }
    public function datauseviewAction() {
	    $kode = $_REQUEST['kode'];
	//   echo "kode--> ".$kode.'<br>';
	   $this->view->lihat_datadtl = $this->adm_datadtl_serv->getDataBykode($kode);
	} 
    
    
}
?>
<?php
class Rekap_Service {
    private static $instance;
  
    private function __construct() {
    }

    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
    }
	
	//MENU KEGIATAN
	public function getKegListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$result = $db->fetchAll('SELECT KodeKegiatan,NamaKegiatan FROM kegiatan order by NamaKegiatan');		 
			$jmlResult = count($result);
			return $result;
	    } catch (Exception $e) {
			 echo $e->getMessage().'<br>';
			 return 'gagal';
	   }
	}	
	
	public function getKelListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			$result = $db->fetchAll('SELECT Kelurahan FROM m_kelurahan order by Kelurahan');		 
			$jmlResult = count($result);
			return $result;
	    } catch (Exception $e) {
			 echo $e->getMessage().'<br>';
			 return 'gagal';
	   }
	}

	function getrekapedit(){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sqlProses = "select * from kegiatan";				
			$sqlData = $sqlProses;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("KodeKegiatan"  	=>(string)$result->KodeKegiatan,
								"NamaKegiatan"  		=>(string)$result->NamaKegiatan,
								"JadwalAwal"  		=>(string)$result->JadwalAwal,
								"JadwalAkhir"  		=>(string)$result->JadwalAkhir,
								"Instansi"  		=>(string)$result->Instansi,
								"KodeKomponen"  		=>(string)$result->KodeKomponen,
								"KodeSubKomponen"  		=>(string)$result->KodeSubKomponen,
								"KodeDetailSubKomponen"  		=>(string)$result->KodeDetailSubKomponen,
								"Anggaran"  	        =>(string)$result->Anggaran	);
			return $hasilAkhir;					
		} catch (Exception $e) {
	        echo $e->getMessage().'<br>';
		    return 'Data tidak ada <br>';
		}
	}
	
	function getrekapkeledit(){
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sqlProses = "select * from m_kelurahan";				
			$sqlData = $sqlProses;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("kode_kelurahan"  	=>(string)$result->kode_kelurahan,
								"Kelurahan"  		=>(string)$result->Kelurahan);
			return $hasilAkhir;					
		} catch (Exception $e) {
	        echo $e->getMessage().'<br>';
		    return 'Data tidak ada <br>';
		}
	}
}
?>

<?php
class Referensi_Service {
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

public function getKategori() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select id,n_kategori from tr_kategori order by n_kategori asc");
			
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
			$data[$j] = array("id" =>(string)$result[$j]->id,
								"n_kategori" =>(string)$result[$j]->n_kategori);
			}					
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	 
	}
	
public function getBadanusaha() {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select id,n_badanusaha from tr_badanusaha order by n_badanusaha asc");
			
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
			$data[$j] = array("id" =>(string)$result[$j]->id,
								"n_badanusaha" =>(string)$result[$j]->n_badanusaha);
			}					
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	 
	}
		
	
	
}
?>

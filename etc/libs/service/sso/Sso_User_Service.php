<?php
class Sso_User_Service {
    private static $instance;
   
    // A private constructor; prevents direct creation of object
    private function __construct() {
       //echo 'I am constructed';
    }

    // The singleton method
    public static function getInstance() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
    }
	
     public function getDataUser($pengguna,$password) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $rabkaklist = $db->fetchAll("SELECT * FROM pengguna where pengguna = '$pengguna' and password=('$password')");		
		 
	     return $rabkaklist;
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}		
	public function getDataUser1($pengguna,$password) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $ktsandi = md5($password);	 
        
		$sql = "SELECT p.*, i.Instansi FROM pengguna p, m_instansi i where p.pengguna='$pengguna' and p.password=('$password') and p.KodeInstansi=i.KodeInstansi";
		//echo $sql;
		$hasil = $db->fetchRow($sql);		
		return $hasil;
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDataUser2($userid,$paswd) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $hasil = $db->fetchOne("SELECT user_id FROM tm_pegawai where user_id = '$userid' and password=md5('$paswd')");		
		 if($hasil){
		 return $hasil;
		 }
	     return '0';
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	public function getUsername($pengguna) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $hasil = $db->fetchOne("SELECT *
								FROM pengguna
								where pengguna='$pengguna'");	
		 return $hasil;
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDataUserNama($userid) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $hasil = $db->fetchOne("SELECT * FROM pengguna  where pengguna='$pengguna'");	
		 return $hasil;
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}
	
	public function getDataUserGroup($usergroup) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		 $db->setFetchMode(Zend_Db::FETCH_OBJ);
		 $ktsandi = md5($paswd);
		 
         $hasil = $db->fetchRow("select c_group from pengguna where c_group in ($usergroup) ");
		 
		 return $hasil;
		 
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	

}
?>
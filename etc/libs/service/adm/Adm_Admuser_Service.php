<?php
class Adm_Admuser_Service {
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
	public function getnamaGroup($group_id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			//$globalRef = new globalReferensi;
			
			$sqlProses = "select a.keterangan
							from tr_groupuser a
							where id = '$group_id'";	
							
			
			$sqlData = $sqlProses; 
			$keterangan = $db->fetchOne($sqlData);				
			
			return $keterangan;					  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	//======================================================================
	// List User
	//======================================================================
	public function cariUserList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		$kategoriCari 	= $dataMasukan['kategoriCari'];
		$katakunciCari 	= $dataMasukan['katakunciCari'];
		$sortBy			= $dataMasukan['sortBy'];
		$sort			= $dataMasukan['sort'];
	   
	   
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;

			$whereBase = " where (c_statusdelete != 'Y' or c_statusdelete is null)";
			$whereOpt = " $kategoriCari like '%$katakunciCari%' ";
			if($katakunciCari != "") { $where = $whereBase." and ".$whereOpt;} 
			else { $where = $whereBase;}
			$order = " order by id ";
			$sqlProses = "select id, username, name,user_id, kd_status, password, c_group, c_statusdelete, i_entry,d_entry	from tm_user".$where;	
			$sqlProses1 = $sqlProses.$order;
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);

			}
			else
			{
				
				//$sqlProses2 = $this->limit2($sqlProses1,$xLimit, $xOffset,$total);
				$sqlData = $sqlProses.$order." limit $xLimit offset $xOffset";
				//echo $sqlData;
				//$sqlData = $sqlProses2;
				$result = $db->fetchAll($sqlData);
				
					
			}
			
			
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id"  			=>(string)$result[$j]->id,
										"username"  	=>(string)$result[$j]->username,
										"name"  	=>(string)$result[$j]->name,
										"user_id"  		=>(string)$result[$j]->user_id,
										"kd_status" 	=>(string)$result[$j]->kd_status,
										"password"  	=>(string)$result[$j]->password,
										"c_group"  		=>(string)$result[$j]->c_group,
										"c_statusdelete"=>(string)$result[$j]->c_statusdelete,
										"i_entry"      	=>(string)$result[$j]->i_entry,
										"d_entry"      	=>(string)$result[$j]->d_entry
										);
				var_dump($hasilAkhir);				
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getUserListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll("SELECT * FROM tm_user where c_statusdelete != 'Y'");
				
		 
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function userInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("username"  	=>$dataMasukan['username'],
								"name"  	=>$dataMasukan['name'],
								"user_id" 		=>$dataMasukan['user_id'],
								"kd_status" 	=>$dataMasukan['kd_status'],
								"password"  	=>md5($dataMasukan['password']),
								"c_group"  		=>$dataMasukan['c_group']);	
			//var_dump($paramInput);
			
			$db->insert('tm_user',$paramInput);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "berhasil.";
			}
	   }
	}

	public function detailUserById($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = " where id = '$id' ";
			$sqlProses = "select id, username, name, user_id, kd_status, password, c_group, c_statusdelete, i_entry,d_entry	from tm_user";	

			
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("id"  		    =>(string)$result->id,
								"user_id"  		=>(string)$result->user_id,
								"name"  	        =>(string)$result->name,
								"username"  	=>(string)$result->username,
								"kd_status" 	=>(string)$result->kd_status,
								"password"  	=>(string)$result->password,
								"c_group"  		=>(string)$result->c_group,
								"c_statusdelete"=>(string)$result->c_statusdelete,
								"i_entry"      	=>(string)$result->i_entry,
								"d_entry"      	=>(string)$result->d_entry
								);
			//var_dump($hasilAkhir);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function userUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("username"  	=>$dataMasukan['username'],
								"name"  	=>$dataMasukan['name'],
								"user_id" 		=>$dataMasukan['user_id'],
								"kd_status" 	=>$dataMasukan['kd_status'],
								"c_group"  		=>$dataMasukan['c_group'],
								"password"  	=>md5($dataMasukan['password'])
				);
			//var_dump($dataMasukan);
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tm_user',$paramInput, $where);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}
	
	public function ubahStatus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("kd_status" 	=>$dataMasukan['kd_status']);
				
			//var_dump($paramInput);
			$where[] = " user_id = '".$dataMasukan['user_id']."'";
			
			$db->update('tm_user',$paramInput, $where);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}

	public function userHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y');	
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tm_user',$paramInput, $where);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "sukses";
			}
	   }
	}
	
	public function ubahPasswd(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			
			//var_dump($dataMasukan);
			$paramInput = array("password"	=> $dataMasukan['password']);	
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tm_user',$paramInput, $where);
			$db->commit();
			
			return 'sukses';
		} catch (Exception $e) {
			$db->rollBack();
			$errmsgArr = explode(":",$e->getMessage());
			
			$errMsg = $errmsgArr[0];

			if($errMsg == "SQLSTATE[23000]")
			{
				return "gagal.Data Sudah Ada.";
			}
			else
			{
				return "gagal.";
			}
	   }
	}
	

/// tambahan hendar 03032010	
public function getTmUser($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$sql = "select id,username,name,user_id,password,c_group from tm_user where 1=1 ". $cari;
			//echo $sql;
		    $result = $db->fetchAll($sql);

			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {			
				$data[$j] = array("id"=>(string)$result[$j]->id,
								"username"=>(string)$result[$j]->username,
								"name"=>(string)$result[$j]->username,
								"user_id"=>(String)$result[$j]->user_id,
								"c_group"=>(String)$result[$j]->c_group,
								"password"=>(string)$result[$j]->password);
				}
						
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	 
	}		
}

?>

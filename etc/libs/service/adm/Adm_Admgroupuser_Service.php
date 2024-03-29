<?php
class Adm_Admgroupuser_Service {
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

	//======================================================================
	// List Groupuser
	//======================================================================

	public function getGroupuserListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT id, n_nama,e_ket,n_level,keterangan FROM tr_groupuser order by id');
				
		 
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function cariGroupuserList(array $dataMasukan, $pageNumber, $itemPerPage) {

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
			$order = " order by $sortBy $sort ";
			$sqlProses = "select id, n_nama,e_ket,n_level,keterangan,  c_statusdelete, i_entry, d_entry	from tr_groupuser";	

			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses"." "."$where) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlData = $sqlProses.$where.$order;//." limit $xLimit offset $xOffset";
				$result = $db->fetchAll($sqlData);	
			}
			
			//echo $sqlData;
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id"  		=>(string)$result[$j]->id,
										"n_nama"  	=>(string)$result[$j]->n_nama,
										"e_ket"		=>(string)$result[$j]->e_ket,
										"n_level"		=>(string)$result[$j]->n_level,
										"keterangan"  =>(string)$result[$j]->keterangan,
										"c_statusdelete"=>(string)$result[$j]->c_statusdelete,
										"i_entry"      	=>(string)$result[$j]->i_entry,
										"d_entry"      	=>(string)$result[$j]->d_entry
										);
				//var_dump($hasilAkhir);				
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function groupuserInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("n_nama"  	=>$dataMasukan['n_nama'],
								"n_level"  	=>$dataMasukan['n_level'],
								"keterangan"  	=>$dataMasukan['keterangan'],
								"e_ket"  	=>$dataMasukan['e_ket']);		
			$db->insert('tr_groupuser',$paramInput);
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

	public function detailGroupuserById($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = " where id = '$id' ";
			$sqlProses = "select id, n_nama, e_ket,n_level,keterangan, c_statusdelete, i_entry,d_entry	from tr_groupuser";	

			
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("id"  		=>(string)$result->id,
								"n_nama"  	=>(string)$result->n_nama,
								"e_ket"     =>(string)$result->e_ket,
								"n_level"     =>(string)$result->n_level,
								"keterangan"     =>(string)$result->keterangan,
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

	public function groupuserUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("n_nama"  	=>$dataMasukan['n_nama'],
				                "n_level"  	=>$dataMasukan['n_level'],
								"keterangan"  	=>$dataMasukan['keterangan'],
								"e_ket"  	=>$dataMasukan['e_ket']);	
			//var_dump($paramInput);
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tr_groupuser',$paramInput, $where);
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

	public function groupuserHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y');	
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tr_groupuser',$paramInput, $where);
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
		
}
?>

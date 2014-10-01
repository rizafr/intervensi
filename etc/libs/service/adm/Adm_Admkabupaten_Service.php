<?php
class Adm_Admkabupaten_Service {
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
	// List Kabupaten
	//======================================================================

	public function getKabupatenListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT  id,  id_prop, n_kab, LTRIM(RTRIM(c_kab)) as c_kab, c_statusdelete, i_entry, d_entry	from tr_kabupaten order by n_kab');
				
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function cariKabupatenList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {

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
			$sqlProses = "SELECT  id, id_prop, n_kab, c_kab, c_statusdelete, i_entry, d_entry	from tr_kabupaten ".$where;	
			
			$sqlProses1 = $sqlProses.$order;
			if(($pageNumber==0) && ($itemPerPage==0))
			{	
				$sqlTotal = "select count(*) from ($sqlProses) a";
				$hasilAkhir = $db->fetchOne($sqlTotal);	
			}
			else
			{
				$sqlProses2 = $this->limit2($sqlProses1,$xLimit, $xOffset,$total);
				$sqlData = $sqlProses2;
				$result = $db->fetchAll($sqlData);	//echo $sqlProses2;
			}
			
			$jmlResult = count($result);
			
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id"  		=>(string)$result[$j]->id,
										"n_kab"  	=>(string)$result[$j]->n_kab,
										"id_prop"  	=>(string)$result[$j]->id_prop,
										"c_kab"  	=>(string)$result[$j]->c_kab,
										"c_statusdelete"		=>(string)$result[$j]->c_statusdelete,
										"i_entry"      			=>(string)$result[$j]->i_entry,
										"d_entry"      			=>(string)$result[$j]->d_entry
										);
				//var_dump($hasilAkhir);				
			}	
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function kabupatenInsert(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("id_prop"		=>$dataMasukan['id_prop'],
								"n_kab"		=>$dataMasukan['n_kab'],
								"c_kab"			=>$dataMasukan['c_kab']);		
			$db->insert('tr_kabupaten',$paramInput);
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

	public function detailKabupatenById($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = " where id = '$id' ";
			$sqlProses = "SELECT id,  id_prop,n_kab,  c_kab, c_statusdelete, i_entry, d_entry	from tr_kabupaten ";	

/*$sp = $dbAdapter->prepare('CALL updateResultsByEvent(?, ?, ?, ?)');
$sp->bindParam(1,$id);
$sp->bindParam(2,$event->name);
$sp->bindParam(3,$event->distance);
$sp->bindParam(4,$event->unit);
// print $sp;????
$sp->execute();*/
			
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("id"  					=>(string)$result->id,
								"id_prop"				=>(string)$result->id_prop,
								"n_kab"					=>(string)$result->n_kab,
								"c_kab"  				=>(string)$result->c_kab,
								"c_statusdelete"		=>(string)$result->c_statusdelete,
								"i_entry"      			=>(string)$result->i_entry,
								"d_entry"      			=>(string)$result->d_entry
								);
			//var_dump($hasilAkhir);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function detailKabupatenByProp($id) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$where = " where id_prop = '$id'  order by n_kab";
		$sqlProses = "SELECT  id,id_prop,n_kab, c_kab,c_statusdelete,i_entry,d_entry	from tr_kabupaten ";
		$sqlData = $sqlProses.$where;
		//echo $sqlData;
		//var_dump($sqlData);
		$result = $db->fetchAll($sqlData);
        $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function detailKabupatenTinggalByProp($id) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$where = " where id_prop = '$id'  order by n_kab";
		$sqlProses = "SELECT  id,id_prop,n_kab,c_kab,c_statusdelete,i_entry,d_entry	from tr_kabupaten ";
		$sqlData = $sqlProses.$where;
		//var_dump($sqlData);
		$result = $db->fetchAll($sqlData);
        $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function detailKabupatenByProp22($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = " where id_prop = '$id' ";
			$sqlProses = "SELECT id,  id_prop,n_kab,  c_kab, c_statusdelete, i_entry, d_entry	from tr_kabupaten ";	
			$sqlData = $sqlProses.$where;
			var_dump($sqlData);
			$result = $db->fetchAll($sqlData);	
			$jmlResult = count($result);var_dump($jmlResult);
			for ($j = 0; $j < $jmlResult; $j++) {
				$hasilAkhir[$j] = array("id"  				=>(string)$result[$j]->id,
										"n_kab"  			=>(string)$result[$j]->n_kab,
										"id_prop"  			=>(string)$result[$j]->id_prop,
										"c_kab"  			=>(string)$result[$j]->c_kab,
										"c_statusdelete"	=>(string)$result[$j]->c_statusdelete,
										"i_entry"      		=>(string)$result[$j]->i_entry,
										"d_entry"      		=>(string)$result[$j]->d_entry
										);
				//var_dump($hasilAkhir);				
			}
			/*$hasilAkhir = array("id"  				=>(string)$result->id,
								"id_prop"			=>(string)$result->id_prop,
								"n_kab"			=>(string)$result->n_kab,
								"c_kab"  			=>(string)$result->c_kab,
								"c_statusdelete"		=>(string)$result->c_statusdelete,
								"i_entry"      			=>(string)$result->i_entry,
								"d_entry"      			=>(string)$result->d_entry
								);
			var_dump($hasilAkhir);*/
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function kabupatenUpdate(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("id_prop"		=>$dataMasukan['id_prop'],
								"n_kab"		=>$dataMasukan['n_kab'],
								"c_kab"			=>$dataMasukan['c_kab']
								);

			//var_dump($paramInput);
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tr_kabupaten',$paramInput, $where);
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

	public function kabupatenHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y');	
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tr_kabupaten',$paramInput, $where);
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
	     public function limit2($sql, $count, $offset = 0,$total)
     {
        $count = intval($count);
        if ($count <= 0) {
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument count=$count is not valid");
        }

        $offset = intval($offset);
        if ($offset < 0) {
            /** @see Zend_Db_Adapter_Exception */
            require_once 'Zend/Db/Adapter/Exception.php';
            throw new Zend_Db_Adapter_Exception("LIMIT argument offset=$offset is not valid");
        }
		$inner_sort = "desc";
		$outer_sort = "asc";
		$top2 = ($count+$offset);
			if(($count + $offset) > $total){
               $offset = $total%$count;
			   $inner_sort = "asc";
			   $outer_sort = "asc";
			   $top2 = $offset;
			}
        $orderby = stristr($sql, 'ORDER BY');
        if ($orderby !== false) {
            //$sort  = (stripos($orderby, ' desc') !== false) ? 'desc' : 'asc';
            $order = str_ireplace('ORDER BY', '', $orderby);
            $order = trim(preg_replace('/\bASC\b|\bDESC\b/i', '', $order));
        }

        $sql = preg_replace('/^SELECT\s/i', 'SELECT TOP ' . $top2 . ' ', $sql);

        $sql = 'SELECT * FROM (SELECT TOP ' . $count . ' * FROM (' . $sql . ' '.$inner_sort.') AS inner_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ';
            $sql .= $outer_sort;
        }
        $sql .= ') AS outer_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ' . $outer_sort;
        }

        return $sql;
    }	
}
?>

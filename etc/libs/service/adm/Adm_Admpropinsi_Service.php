<?php
class Adm_Admpropinsi_Service {
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
	// List Propinsi
	//======================================================================

	public function getPropinsiListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT id, n_propinsi, LTRIM(RTRIM(c_propinsi)) as c_propinsi FROM tr_propinsi order by n_propinsi');
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function cariPropinsiList(array $dataMasukan, $pageNumber, $itemPerPage,$total) {
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
			$sqlProses = "SELECT  id, n_propinsi, c_propinsi, c_statusdelete, i_entry, d_entry FROM  tr_propinsi ".$where;	
			
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
										"n_propinsi"  	=>(string)$result[$j]->n_propinsi,
										"c_propinsi"=>(string)$result[$j]->c_propinsi,
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

	public function propinsiInsert(array $dataMasukan) { //var_dump($dataMasukan); 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("n_propinsi"  	=>$dataMasukan['n_propinsi'],
								"c_propinsi"=>$dataMasukan['c_propinsi']);	
			
			$db->insert('tr_propinsi',$paramInput);
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

	public function detailPropinsiById($id) {

		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$where = " where id = '$id' ";
			$sqlProses = "SELECT  id, n_propinsi, c_propinsi, c_statusdelete, i_entry, d_entry FROM  tr_propinsi";	

			
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			
			$hasilAkhir = array("id"  		=>(string)$result->id,
								"n_propinsi"  	=>(string)$result->n_propinsi,
								"c_propinsi"=>(string)$result->c_propinsi,
								"c_statusdelete"=>(string)$result->c_statusdelete,
								"i_entry"      	=>(string)$result->i_entry,
								"d_entry"      	=>(string)$result->d_entry
								);
			//var_dump("---------------->".$result->n_propinsi);
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function propinsiUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');//var_dump("------masuk---------->");
		try {
			$db->beginTransaction();
			$paramInput = array("n_propinsi"  	=>$dataMasukan['n_propinsi']
								);	
			//var_dump("------------------------->"+$paramInput);
								
			$where[] = " c_propinsi = '".$dataMasukan['c_propinsi']."'";
			
			$db->update('tr_propinsi',$paramInput, $where);
			$db->commit();

		/*$sp = $dbAdapter->prepare('CALL PInsProp(?, ?)');
		$sp->bindParam(1,$id);
		$sp->bindParam(2,$event->name);
		$sp->bindParam(3,$event->distance);
		$sp->bindParam(4,$event->unit);
		// print $sp;????
		$sp->execute();*/


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

	public function propinsiHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y');	
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tr_propinsi',$paramInput, $where);
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

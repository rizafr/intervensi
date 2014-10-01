<?php
class Adm_Admvariabel_Service {
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
	// List Variabel
	//======================================================================

	public function getVariabelListAll() {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db->fetchAll('SELECT  id, c_var, n_var, c_statusdelete, i_entry, d_entry  FROM  tr_variabel ');
				
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function getVariabelListBy($prodi) {
	
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		$where = " where c_prodi = '$prodi' ";
		$order = " order by n_variabel ";
		$sqlProses = "SELECT  id, c_var, n_var, c_statusdelete, i_entry, d_entry  FROM  tr_variabel  ";	
		$sqlData = $sqlProses.$where.$order;
		//echo "variabel--->".$sqlData;
		$result = $db->fetchAll($sqlData);
         $jmlResult = count($result);
		 return $result;
	    } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function cariVariabelList(array $dataMasukan, $pageNumber, $itemPerPage) {
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
			$sqlProses = "SELECT  id, c_var, n_var, c_statusdelete, i_entry, d_entry  FROM  tr_variabel ";	

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
										"n_var"  	=>(string)$result[$j]->n_var,
										"c_var"=>(string)$result[$j]->c_var,
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

	public function variabelInsert(array $dataMasukan) { //var_dump($dataMasukan); 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_var"  	=>$dataMasukan['c_var'],
								"n_var"=>$dataMasukan['n_var']);	
			
			$db->insert('tr_variabel',$paramInput);
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

	public function detailVariabelById($id) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where id = '$id' ";
			$sqlProses = "SELECT  id, c_var, n_var, c_statusdelete, i_entry, d_entry  FROM  tr_variabel";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			$hasilAkhir = array("id"  			=>(string)$result->id,
								"c_var"  		=>(string)$result->c_var,
								"n_var"			=>(string)$result->n_var,
								"c_statusdelete"=>(string)$result->c_statusdelete,
								"i_entry"      	=>(string)$result->i_entry,
								"d_entry"      	=>(string)$result->d_entry
								);
			return $hasilAkhir;						  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function getvariabel($var) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$where = " where c_var = '$var' ";
			$sqlProses = "SELECT  id, c_var, n_var, c_statusdelete, i_entry, d_entry  FROM  tr_variabel";	
			$sqlData = $sqlProses.$where;
			$result = $db->fetchRow($sqlData);	
			$hasilAkhir = array("id"  			=>(string)$result->id,
								"c_var"  		=>(string)$result->c_var,
								"n_var"			=>(string)$result->n_var,
								"c_statusdelete"=>(string)$result->c_statusdelete,
								"i_entry"      	=>(string)$result->i_entry,
								"d_entry"      	=>(string)$result->d_entry
								);
			return $hasilAkhir;						  
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}

	public function variabelUpdate(array $dataMasukan) { 
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');//var_dump("------masuk---------->");
		try {
			$db->beginTransaction();
			$paramInput = array("n_variabel"  	=>$dataMasukan['n_variabel'],
								"c_variabel"  	=>$dataMasukan['c_variabel']
								);	
			//var_dump("------------------------->"+$paramInput);
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tr_variabel',$paramInput, $where);
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

	public function variabelHapus(array $dataMasukan) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->beginTransaction();
			$paramInput = array("c_statusdelete"	=> 'Y');	
								
			$where[] = " id = '".$dataMasukan['id']."'";
			
			$db->update('tr_variabel',$paramInput, $where);
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
	
	
	
	public function getTrVariabel($cari, $pageNumber, $itemPerPage) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
	   
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		 
			$xLimit=$itemPerPage;
			$xOffset=($pageNumber-1)*$itemPerPage;
			
			if(($pageNumber==0) && ($itemPerPage==0))
			{
				$data = $db->fetchOne("select count(*) from tr_variabel where 1=1 $cari ");
			}
			else
			{
				$sqlProses1= "select id,c_var,n_var,c_statusdelete,i_entry,d_entry from tr_variabel where 1=1 $cari";
				$sqlProses2 = $this->limit2($sqlProses1,$xLimit, $xOffset);
				$sqlData = $sqlProses2;
				$result = $db->fetchAll($sqlData);			
				$jmlResult = count($result);
			
				for ($j = 0; $j < $jmlResult; $j++) {
					$hasilAkhir[$j] = array("id"  		=>(string)$result[$j]->id,
											"n_var"  	=>(string)$result[$j]->n_var,
											"c_var"=>(string)$result[$j]->c_var,
											"c_statusdelete"=>(string)$result[$j]->c_statusdelete,
											"i_entry"      	=>(string)$result[$j]->i_entry,
											"d_entry"      	=>(string)$result[$j]->d_entry
											);
		
				}
			}			
			return $hasilAkhir;						  
			
	   } catch (Exception $e) {
         echo $e->getMessage().'<br>';
	     return 'gagal <br>';
	   }
	}	
	
	
	public function tambahDataVariabel(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $tambah_data = array(	"n_var"=>$data['n_var'],
								"c_var"=>$data['c_var'],
								"c_statusdelete"=>$data['c_statusdelete'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
	     $db->insert('tr_variabel',$tambah_data);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function ubahDataVariabel(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ubah_data = array(	"n_var"=>$data['n_var'],
								"c_var"=>$data['c_var'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tr_variabel',$ubah_data, "id = '".trim($data['id'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function hapusDataVariabel(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ubah_data = array(	"c_statusdelete"=>$data['c_statusdelete'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tr_variabel',$ubah_data, "id = '".trim($data['id'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
    public function limit2($sql, $count, $offset = 0)
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

        $orderby = stristr($sql, 'ORDER BY');
        if ($orderby !== false) {
            $sort  = (stripos($orderby, ' desc') !== false) ? 'desc' : 'asc';
            $order = str_ireplace('ORDER BY', '', $orderby);
            $order = trim(preg_replace('/\bASC\b|\bDESC\b/i', '', $order));
        }

        $sql = preg_replace('/^SELECT\s/i', 'SELECT TOP ' . ($count+$offset) . ' ', $sql);

        $sql = 'SELECT * FROM (SELECT TOP ' . $count . ' * FROM (' . $sql . ') AS inner_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ';
            $sql .= (stripos($sort, 'asc') !== false) ? 'DESC' : 'ASC';
        }
        $sql .= ') AS outer_tbl';
        if ($orderby !== false) {
            $sql .= ' ORDER BY ' . $order . ' ' . $sort;
        }

        return $sql;
    }	
		
}
?>

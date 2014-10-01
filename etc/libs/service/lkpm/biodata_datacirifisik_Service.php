<?php
class Biodata_DataCiriFisik_Service {
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
public function getTmCiriFisik($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;				
				$result = $db->fetchAll("select id,i_nik,q_berat,q_tinggi,n_rambut,n_muka,n_warnakulit,n_cacattubuh,n_cirikhas,
										i_entry,d_entry from tm_cirifisik where 1=1 $cari order by d_entry asc ");

			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
			
			
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"id" =>(string)$result[$j]->id,
									"q_tinggi" =>(string)$result[$j]->q_tinggi,
									"q_berat" =>(string)$result[$j]->q_berat,
									"n_rambut" =>(string)$result[$j]->n_rambut,
									"n_muka" =>(string)$result[$j]->n_muka,
									"n_warnakulit" =>(string)$result[$j]->n_warnakulit,
									"n_cacattubuh" =>(string)$result[$j]->n_cacattubuh,
									"n_cirikhas" =>(string)$result[$j]->n_cirikhas,								
									"i_entry" =>(string)$result[$j]->i_entry,
									"d_entry" =>(string)$result[$j]->d_entry);
				}
						
		     return $data;
		   } catch (Exception $e) {
	         echo $e->getMessage().'<br>';
		     return 'Data tidak ada <br>';
		   }
	 
	}
	
	public function tambahData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();

	     $tambah_data = array("i_nik" =>$data['i_nik'],
								"q_tinggi" =>$data['q_tinggi'],
								"q_berat" =>$data['q_berat'],
								"n_rambut" =>$data['n_rambut'],
								"n_muka" =>$data['n_muka'],
								"n_warnakulit" =>$data['n_warnakulit'],
								"n_cacattubuh" =>$data['n_cacattubuh'],
								"n_cirikhas" =>$data['n_cirikhas'],						
								"i_entry" =>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
								
															
	     $db->insert('tm_cirifisik',$tambah_data);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	

	public function ubahData(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ubah_data = array("i_nik" =>$data['i_nik'],
								"q_tinggi" =>$data['q_tinggi'],
								"q_berat" =>$data['q_berat'],
								"n_rambut" =>$data['n_rambut'],
								"n_muka" =>$data['n_muka'],
								"n_warnakulit" =>$data['n_warnakulit'],
								"n_cacattubuh" =>$data['n_cacattubuh'],
								"n_cirikhas" =>$data['n_cirikhas'],						
								"i_entry" =>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_cirifisik',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id = '".trim($data['id'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


	public function hapusData($i_nik,$id) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and id= '".$id."' ";
		 $db->delete('tm_cirifisik', $where);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	
}
?>

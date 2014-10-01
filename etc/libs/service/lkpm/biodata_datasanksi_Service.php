<?php
class Biodata_DataSanksi_Service {
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
public function getTmHukuman($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
				$result = $db->fetchAll("select i_nik,i_hukuman,n_hukuman,c_hukuman,d_hukuman,n_pejabat,
										e_hukuman,i_entry,d_entry,id,
										cast(Month(d_hukuman)AS VARCHAR) as bulanhukuman,
										DATENAME(d, d_hukuman) as tanggalhukuman,
										DATENAME(yyyy, d_hukuman) as tahunhukuman										
										from tm_hukuman where 1=1 $cari order by i_nik desc");

									
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"id" =>(string)$result[$j]->id,
									"i_hukuman" =>(string)$result[$j]->i_hukuman,
									"n_hukuman" =>(string)$result[$j]->n_hukuman,
									"c_hukuman" =>(string)$result[$j]->c_hukuman,
									"d_hukuman" =>(string)$result[$j]->d_hukuman,
									"n_pejabat" =>(string)$result[$j]->n_pejabat,
									"e_hukuman" =>(string)$result[$j]->e_hukuman,
									"bulanhukuman" =>(string)$result[$j]->bulanhukuman,
									"tanggalhukuman" =>(string)$result[$j]->tanggalhukuman,
									"tahunhukuman" =>(string)$result[$j]->tahunhukuman,
									);
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

	     $tambah_data = array(		"i_nik"=>$data['i_nik'],
									"i_hukuman" =>$data['i_hukuman'],
									"n_hukuman" =>$data['n_hukuman'],
									"c_hukuman" =>$data['c_hukuman'],
									"d_hukuman" =>$data['d_hukuman'],
									"n_pejabat" =>$data['n_pejabat'],
									"e_hukuman" =>$data['e_hukuman'],
									"d_entry"=>date("Y-m-d"));
	     $db->insert('tm_hukuman',$tambah_data);
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
	     $ubah_data = array("i_nik"=>$data['i_nik'],
							"i_hukuman" =>$data['i_hukuman'],
							"n_hukuman" =>$data['n_hukuman'],
							"c_hukuman" =>$data['c_hukuman'],
							"d_hukuman" =>$data['d_hukuman'],
							"n_pejabat" =>$data['n_pejabat'],
							"e_hukuman" =>$data['e_hukuman'],
							"d_entry"=>date("Y-m-d"));

		$db->update('tm_hukuman',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id = '".trim($data['id'])."' ");
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
		 $where[] = "i_nik = '".$i_nik."' and id = '".$id."' ";
		 $db->delete('tm_hukuman', $where);
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

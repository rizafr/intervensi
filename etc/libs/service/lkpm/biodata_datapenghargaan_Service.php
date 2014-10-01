<?php
class Biodata_DataPenghargaan_Service {
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
public function getTmPenghargaan($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $db->fetchAll("select id, i_nik,i_srt_penghargaan,n_penghargaan,d_penghargaan,n_pejabat,a_file,e_penghargaan	,i_entry,d_entry	
										from tm_penghargaan where 1=1 $cari order by d_penghargaan");

			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("id" =>(string)$result[$j]->id,	
									"i_nik" =>(string)$result[$j]->i_nik,	
									"i_srt_penghargaan" =>(string)$result[$j]->i_srt_penghargaan,
									"n_penghargaan" =>(string)$result[$j]->n_penghargaan,
									"d_penghargaan" =>(string)$result[$j]->d_penghargaan,
									"n_pejabat" =>(string)$result[$j]->n_pejabat,
									"e_penghargaan" =>(string)$result[$j]->e_penghargaan,
									"a_file" =>(string)$result[$j]->a_file,
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

	     $tambah_data = array(		"i_nik" =>$data['i_nik'],
									"i_srt_penghargaan" =>$data['i_srt_penghargaan'],
									"n_penghargaan" =>$data['n_penghargaan'],
									"d_penghargaan" =>$data['d_penghargaan'],
									"n_pejabat" =>$data['n_pejabat'],
									"e_penghargaan" =>$data['e_penghargaan'],
									"a_file" =>$data['a_file'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));
									
	     $db->insert('tm_penghargaan',$tambah_data);
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
	     $ubah_data = array(		"i_nik" =>$data['i_nik'],
									"i_srt_penghargaan" =>$data['i_srt_penghargaan'],
									"n_penghargaan" =>$data['n_penghargaan'],
									"d_penghargaan" =>$data['d_penghargaan'],
									"n_pejabat" =>$data['n_pejabat'],
									"e_penghargaan" =>$data['e_penghargaan'],
									"a_file" =>$data['a_file'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		$db->update('tm_penghargaan',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and i_srt_penghargaan = '".trim($data['i_srt_penghargaan2'])."' ");
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
		 $db->delete('tm_penghargaan', $where);
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

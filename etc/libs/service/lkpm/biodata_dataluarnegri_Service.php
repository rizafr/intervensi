<?php
class Biodata_DataLuarNegri_Service {
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
public function getTmPdLn($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select i_nik,id,d_pd_pergi,n_pd_negara,e_pd_tujuan,q_pd_lama,e_lama,n_pd_sumberbiaya,e_keterangan,i_entry,d_entry,
										cast(Month(d_pd_pergi)AS VARCHAR) as bulanpergi,
										DATENAME(d, d_pd_pergi) as tanggalpergi,
										DATENAME(yyyy, d_pd_pergi) as tahunpergi	
										from tm_pd_ln where 1=1 $cari order by id desc");
	
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,	
									"id" =>(string)$result[$j]->id,
									"d_pd_pergi" =>(string)$result[$j]->d_pd_pergi,
									"e_lama" =>(string)$result[$j]->e_lama,
									"n_pd_negara" =>(string)$result[$j]->n_pd_negara,
									"e_pd_tujuan" =>(string)$result[$j]->e_pd_tujuan,
									"q_pd_lama" =>(string)$result[$j]->q_pd_lama,
									"n_pd_sumberbiaya" =>(string)$result[$j]->n_pd_sumberbiaya,
									"e_keterangan" =>(string)$result[$j]->e_keterangan,
									"i_entry" =>(string)$result[$j]->i_entry,
									"d_entry" =>(string)$result[$j]->d_entry,
									"tanggalpergi"=>(String)$result[$j]->tanggalpergi,	
									"bulanpergi"=>(String)$result[$j]->bulanpergi,	
									"tahunpergi"=>(String)$result[$j]->tahunpergi,);
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
									"d_pd_pergi" =>$data['d_pd_pergi'],
									"e_lama" =>$data['e_lama'],
									"n_pd_negara" =>$data['n_pd_negara'],
									"e_pd_tujuan" =>$data['e_pd_tujuan'],
									"q_pd_lama" =>$data['q_pd_lama'],
									"n_pd_sumberbiaya" =>$data['n_pd_sumberbiaya'],
									"e_keterangan" =>$data['e_keterangan'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));
									
	     $db->insert('tm_pd_ln',$tambah_data);
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
	     $ubah_data = array(		"d_pd_pergi" =>$data['d_pd_pergi'],
									"e_lama" =>$data['e_lama'],
									"n_pd_negara" =>$data['n_pd_negara'],
									"e_pd_tujuan" =>$data['e_pd_tujuan'],
									"q_pd_lama" =>$data['q_pd_lama'],
									"n_pd_sumberbiaya" =>$data['n_pd_sumberbiaya'],
									"e_keterangan" =>$data['e_keterangan'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		$db->update('tm_pd_ln',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and id = '".trim($data['id'])."' ");
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
		 $db->delete('tm_pd_ln', $where);
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

<?php
class Biodata_DataPenelitian_Service {
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
public function getTmPenelitian($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
			
				$result = $db->fetchAll("select i_nik,c_penelitian,n_penelitian,d_penelitian,
									cast(Month(d_penelitian)AS VARCHAR) as bulanpenelitian,
									DATENAME(d, d_penelitian) as tanggalpenelitian,
									DATENAME(yyyy, d_penelitian) as tahunpenelitian,
									e_judul,a_tempat,v_dana,n_sumberdana,i_entry,d_entry,c_jabatan	
									from tm_penelitian where 1=1 $cari order by c_penelitian desc ");
										

						
							
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,	
									"c_penelitian" =>(string)$result[$j]->c_penelitian,
									"n_penelitian" =>(string)$result[$j]->n_penelitian,
									"d_penelitian" =>(string)$result[$j]->d_penelitian,
									"bulanpenelitian" =>(string)$result[$j]->bulanpenelitian,
									"tanggalpenelitian" =>(string)$result[$j]->tanggalpenelitian,
									"tahunpenelitian" =>(string)$result[$j]->tahunpenelitian,
									"e_judul" =>(string)$result[$j]->e_judul,
									"a_tempat" =>(string)$result[$j]->a_tempat,
									"v_dana"=>(string)$result[$j]->v_dana,
									"n_sumberdana"=>(string)$result[$j]->n_sumberdana,
									"c_jabatan"=>(string)$result[$j]->c_jabatan,
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
									"n_penelitian" =>$data['n_penelitian'],
									"d_penelitian" =>$data['d_penelitian'],
									"e_judul" =>$data['e_judul'],
									"a_tempat" =>$data['a_tempat'],
									"v_dana"=>$data['v_dana'],
									"n_sumberdana"=>$data['n_sumberdana'],
									"c_jabatan"=>$data['c_jabatan'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));	
									
	     $db->insert('tm_penelitian',$tambah_data);
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
									"n_penelitian" =>$data['n_penelitian'],
									"d_penelitian" =>$data['d_penelitian'],
									"e_judul" =>$data['e_judul'],
									"a_tempat" =>$data['a_tempat'],
									"v_dana"=>$data['v_dana'],
									"n_sumberdana"=>$data['n_sumberdana'],
									"c_jabatan"=>$data['c_jabatan'],
									"i_entry" =>$data['i_entry'],
									"d_entry"=>date("Y-m-d"));

		$db->update('tm_penelitian',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_penelitian = '".trim($data['c_penelitian'])."' ");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function hapusData($i_nik,$c_penelitian) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_penelitian = '".$c_penelitian."' ";
		 $db->delete('tm_penelitian', $where);
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

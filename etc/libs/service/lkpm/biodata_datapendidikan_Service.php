<?php
class Biodata_Datapendidikan_Service {
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
public function getTmPendidikan($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 

			
				$xLimit=$itemPerPage;
				$xOffset=($pageNumber-1)*$itemPerPage;				
				$result = $db->fetchAll("select i_nik,c_pendidikan,n_alumni,n_fakultas,n_jurusan,q_ijazah,d_thn_lulus,i_ipk,n_alamat,
										i_entry,d_entry,c_verifikasi,a_file from tm_pendidikan where 1=1 $cari order by d_thn_lulus asc ");

			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
			
			$n_pendidikan = $db->fetchOne('select n_tingkatpend from tr_tingkatpend where c_tingkatpend = ?',$result[$j]->c_pendidikan);
				$data[$j] = array("i_nik" =>(string)$result[$j]->i_nik,
									"c_pendidikan" =>(string)$result[$j]->c_pendidikan,
									"n_pendidikan" =>$n_pendidikan,
									"n_alumni" =>(string)$result[$j]->n_alumni,
									"n_fakultas" =>(string)$result[$j]->n_fakultas,
									"n_jurusan" =>(string)$result[$j]->n_jurusan,
									"q_ijazah" =>(string)$result[$j]->q_ijazah,
									"d_thn_lulus" =>(string)$result[$j]->d_thn_lulus,
									"i_ipk" =>(string)$result[$j]->i_ipk,
									"n_alamat" =>(string)$result[$j]->n_alamat,
									"c_verifikasi" =>(string)$result[$j]->c_verifikasi,
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

	     $tambah_data = array(
								"i_nik"=>$data['i_nik'],
								"c_pendidikan"=>$data['c_pendidikan'],
								"n_alumni"=>$data['n_alumni'],
								"n_fakultas"=>$data['n_fakultas'],
								"n_jurusan"=>$data['n_jurusan'],
								"q_ijazah"=>$data['q_ijazah'],
								"d_thn_lulus"=>$data['d_thn_lulus'],
								"i_ipk"=>$data['i_ipk'],
								"n_alamat"=>$data['n_alamat'],
								"a_file"=>$data['a_file'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
	     $db->insert('tm_pendidikan',$tambah_data);
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
	     $ubah_data = array(	"n_alumni"=>$data['n_alumni'],
								"n_fakultas"=>$data['n_fakultas'],
								"n_jurusan"=>$data['n_jurusan'],
								"q_ijazah"=>$data['q_ijazah'],
								"d_thn_lulus"=>$data['d_thn_lulus'],
								"i_ipk"=>$data['i_ipk'],
								"n_alamat"=>$data['n_alamat'],
								"a_file"=>$data['a_file'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_pendidikan',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_pendidikan = '".trim($data['c_pendidikan'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}


	public function hapusData($i_nik,$c_pendidikan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_pendidikan= '".$c_pendidikan."' ";
		 $db->delete('tm_pendidikan', $where);
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

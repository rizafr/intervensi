<?php
class Biodata_Datajabatan_Service {
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
public function getTmJabatan($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
				$result = $db->fetchAll("select id,i_nik,c_jabatan,d_jabatan,
										cast(Month(d_jabatan)AS VARCHAR) as bulanjabatan,
										DATENAME(d, d_jabatan) as tanggaljabatan,
										DATENAME(yyyy, d_jabatan) as tahunjabatan,
										i_sk,d_sk,
										cast(Month(d_sk)AS VARCHAR) as bulansk,
										DATENAME(d, d_sk) as tanggalsk,
										DATENAME(yyyy, d_sk) as tahunsk,
										n_pejabat_sk,q_tahun_kerja,q_bulan_kerja,i_entry,d_entry,
										a_file
										from tm_jabatan where 1=1 $cari 
										order by d_sk asc");


// echo "select i_nik,c_jabatan,d_jabatan,
										// cast(Month(d_jabatan)AS VARCHAR) as bulanjabatan,
										// DATENAME(d, d_jabatan) as tanggaljabatan,
										// DATENAME(yyyy, d_jabatan) as tahunjabatan,
										// i_sk,d_sk,
										// cast(Month(d_sk)AS VARCHAR) as bulansk,
										// DATENAME(d, d_sk) as tanggalsk,
										// DATENAME(yyyy, d_sk) as tahunsk,
										// n_pejabat_sk,q_tahun_kerja,q_bulan_kerja,i_entry,d_entry
										// from tm_jabatan where 1=1 $cari 
										// order by d_sk asc";										
									
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				$n_jabatan = $db->fetchOne('select n_jbtstruktural from tr_jbtstruktural where c_jbtstruktural = ?',$result[$j]->c_jabatan);						

				$data[$j] = array("id" =>(string)$result[$j]->id,
									"i_nik" =>(string)$result[$j]->i_nik,
									"c_jabatan" =>(string)$result[$j]->c_jabatan,
									"d_jabatan" =>(string)$result[$j]->d_jabatan,
									"i_sk" =>(string)$result[$j]->i_sk,
									"d_sk" =>(string)$result[$j]->d_sk,
									"n_pejabat_sk" =>(string)$result[$j]->n_pejabat_sk,
									"q_tahun_kerja" =>(string)$result[$j]->q_tahun_kerja,
									"q_bulan_kerja" =>(string)$result[$j]->q_bulan_kerja,
									"c_verifikasi" =>(string)$result[$j]->c_verifikasi,									
									"n_jabatan" =>$n_jabatan,
									"tanggaljabatan"=>(string)$result[$j]->tanggaljabatan,
									"bulanjabatan"=>(string)$result[$j]->bulanjabatan,
									"tahunjabatan"=>(string)$result[$j]->tahunjabatan,
									"bulansk"=>(string)$result[$j]->bulansk,
									"tanggalsk"=>(string)$result[$j]->tanggalsk,
									"tahunsk"=>(string)$result[$j]->tahunsk,
									"a_file"=>(string)$result[$j]->a_file,
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
								"c_jabatan"=>$data['c_jabatan'],
								"d_jabatan"=>$data['d_jabatan'],
								"i_sk"=>$data['i_sk'],
								"d_sk"=>$data['d_sk'],
								"n_pejabat_sk"=>$data['n_pejabat_sk'],
								"a_file"=>$data['a_file'],
								"q_tahun_kerja"=>$data['q_tahun_kerja'],
								"q_bulan_kerja"=>$data['q_bulan_kerja'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
	     $db->insert('tm_jabatan',$tambah_data);
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
	     $ubah_data = array(	"i_nik"=>$data['i_nik'],
								"c_jabatan"=>$data['c_jabatan'],
								"d_jabatan"=>$data['d_jabatan'],
								"i_sk"=>$data['i_sk'],
								"d_sk"=>$data['d_sk'],
								"n_pejabat_sk"=>$data['n_pejabat_sk'],
								"a_file"=>$data['a_file'],
								"q_tahun_kerja"=>$data['q_tahun_kerja'],
								"q_bulan_kerja"=>$data['q_bulan_kerja'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));

		$db->update('tm_jabatan',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_jabatan = '".trim($data['c_jabatan'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}

	public function hapusData($i_nik,$c_jabatan) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_jabatan= '".$c_jabatan."' ";
		 $db->delete('tm_jabatan', $where);
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

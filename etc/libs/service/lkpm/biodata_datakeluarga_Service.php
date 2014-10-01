<?php
class biodata_datakeluarga_Service {
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
public function getTmKeluarga($cari) {
		$registry = Zend_Registry::getInstance();
		$db = $registry->get('db');
		try {
			$db->setFetchMode(Zend_Db::FETCH_OBJ); 
		
				$result = $db->fetchAll("select i_nik,c_keluarga,q_keluarga,n_namakeluarga,n_gelar,
										n_tmplahir,d_lahir,c_jnskelamin,c_agama,c_goldarah,
										n_hobby,c_pendidikan,c_identitas,i_identitas,n_alamat,
										c_rt,c_rw,n_kelurahan,n_kecamatan,i_kodepos,c_propinsi,
										c_kota,i_tlprumah,i_handphone,n_email,n_pekerjaan,i_entry,d_entry,										
										cast(Month(d_lahir)AS VARCHAR) as bulanlahir,
										DATENAME(d, d_lahir) as tanggallahir,
										DATENAME(yyyy, d_lahir) as tahunlahir
										from tm_keluarga where 1=1 $cari order by c_keluarga desc");

									
			$jmlResult = count($result);
			for ($j = 0; $j < $jmlResult; $j++) {
				//$n_propinsi = $db->fetchOne('select n_propinsi from tr_propinsi where c_propinsi = ?',$result[$j]->c_propinsi);
				//$n_kota = $db->fetchOne('select n_kabupaten from tr_kabupaten_propinsi where c_kabupaten = ?',$result[$j]->c_kota);
				$n_pekerjaan2 = $db->fetchOne('select n_pekerjaan from tr_pekerjaan where c_pekerjaan = ?',$result[$j]->n_pekerjaan);
				$n_agama = $db->fetchOne('select n_agama from tr_agama where c_agama = ?',$result[$j]->c_agama);
				//$n_hubungan = $db->fetchOne('select n_keluarga from tr_keluarga where c_keluarga = ?',$result[$j]->c_keluarga);

				$data[$j] = array("i_nik"=>(string)$result[$j]->i_nik,
								"c_keluarga"=>(string)$result[$j]->c_keluarga,
								"q_keluarga"=>(string)$result[$j]->q_keluarga,
								"n_namakeluarga"=>(string)$result[$j]->n_namakeluarga,
								"n_gelar"=>(string)$result[$j]->n_gelar,
								"n_tmplahir"=>(string)$result[$j]->n_tmplahir,
								"d_lahir"=>(string)$result[$j]->d_lahir,
								"c_jnskelamin"=>(string)$result[$j]->c_jnskelamin,
								"c_agama"=>(string)$result[$j]->c_agama,
								"n_agama"=>$n_agama,								
								"c_goldarah"=>(string)$result[$j]->c_goldarah,
								"n_hobby"=>(string)$result[$j]->n_hobby,
								"c_pendidikan"=>(string)$result[$j]->c_pendidikan,
								"c_identitas"=>(string)$result[$j]->c_identitas,
								"i_identitas"=>(string)$result[$j]->i_identitas,
								"n_alamat"=>(string)$result[$j]->n_alamat,
								"c_rt"=>(string)$result[$j]->c_rt,
								"c_rw"=>(string)$result[$j]->c_rw,
								"n_kelurahan"=>(string)$result[$j]->n_kelurahan,
								"n_kecamatan"=>(string)$result[$j]->n_kecamatan,
								"i_kodepos"=>(string)$result[$j]->i_kodepos,
								"c_propinsi"=>(string)$result[$j]->c_propinsi,
								"c_kota"=>(string)$result[$j]->c_kota,
								"i_tlprumah"=>(string)$result[$j]->i_tlprumah,
								"i_handphone"=>(string)$result[$j]->i_handphone,
								"n_email"=>(string)$result[$j]->n_email,
								"n_pekerjaan"=>(string)$result[$j]->n_pekerjaan,
								"n_pekerjaan2"=>$n_pekerjaan2,
								"tanggallahir"=>(String)$result[$j]->tanggallahir,	
								"bulanlahir"=>(String)$result[$j]->bulanlahir,	
								"tahunlahir"=>(String)$result[$j]->tahunlahir,
								"n_hubungan"=>$n_hubungan,
								"i_entry"=>(string)$result[$j]->i_entry,
								"d_entry"=>(string)$result[$j]->d_entry);
								
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

	     $tambah_data = array("i_nik"=>$data['i_nik'],
								"c_keluarga"=>$data['c_keluarga'],
								"q_keluarga"=>$data['q_keluarga'],
								"n_namakeluarga"=>$data['n_namakeluarga'],
								"n_gelar"=>$data['n_gelar'],
								"n_tmplahir"=>$data['n_tmplahir'],
								"d_lahir"=>$data['d_lahir'],
								"c_jnskelamin"=>$data['c_jnskelamin'],
								"c_agama"=>$data['c_agama'],
								"c_goldarah"=>$data['c_goldarah'],
								"n_hobby"=>$data['n_hobby'],
								"c_pendidikan"=>$data['c_pendidikan'],
								"c_identitas"=>$data['c_identitas'],
								"i_identitas"=>$data['i_identitas'],
								"n_alamat"=>$data['n_alamat'],
								"c_rt"=>$data['c_rt'],
								"c_rw"=>$data['c_rw'],
								"n_kelurahan"=>$data['n_kelurahan'],
								"n_kecamatan"=>$data['n_kecamatan'],
								"i_kodepos"=>$data['i_kodepos'],
								"c_propinsi"=>$data['c_propinsi'],
								"c_kota"=>$data['c_kota'],
								"i_tlprumah"=>$data['i_tlprumah'],
								"i_handphone"=>$data['i_handphone'],
								"n_email"=>$data['n_email'],
								"n_pekerjaan"=>$data['n_pekerjaan'],
								"i_entry"=>$data['i_entry'],
								"d_entry"=>date("Y-m-d"));
									
	     $db->insert('tm_keluarga',$tambah_data);
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
							"c_keluarga"=>$data['c_keluarga'],
							"q_keluarga"=>$data['q_keluarga'],
							"n_namakeluarga"=>$data['n_namakeluarga'],
							"n_gelar"=>$data['n_gelar'],
							"n_tmplahir"=>$data['n_tmplahir'],
							"d_lahir"=>$data['d_lahir'],
							"c_jnskelamin"=>$data['c_jnskelamin'],
							"c_agama"=>$data['c_agama'],
							"c_goldarah"=>$data['c_goldarah'],
							"n_hobby"=>$data['n_hobby'],
							"c_pendidikan"=>$data['c_pendidikan'],
							"c_identitas"=>$data['c_identitas'],
							"i_identitas"=>$data['i_identitas'],
							"n_alamat"=>$data['n_alamat'],
							"c_rt"=>$data['c_rt'],
							"c_rw"=>$data['c_rw'],
							"n_kelurahan"=>$data['n_kelurahan'],
							"n_kecamatan"=>$data['n_kecamatan'],
							"i_kodepos"=>$data['i_kodepos'],
							"c_propinsi"=>$data['c_propinsi'],
							"c_kota"=>$data['c_kota'],
							"i_tlprumah"=>$data['i_tlprumah'],
							"i_handphone"=>$data['i_handphone'],
							"n_email"=>$data['n_email'],
							"n_pekerjaan"=>$data['n_pekerjaan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));

		$db->update('tm_keluarga',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_keluarga = '".trim($data['c_keluarga'])."' and q_keluarga = '".trim($data['q_keluarga'])."' ");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function ubahDataOrtu(array $data) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
	     $ubah_data = array("i_nik"=>$data['i_nik'],
							"c_keluarga"=>$data['c_keluarga'],
							"q_keluarga"=>$data['q_keluarga'],
							"n_namakeluarga"=>$data['n_namakeluarga'],
							"n_gelar"=>$data['n_gelar'],
							"n_tmplahir"=>$data['n_tmplahir'],
							"d_lahir"=>$data['d_lahir'],
							"c_jnskelamin"=>$data['c_jnskelamin'],
							"c_agama"=>$data['c_agama'],
							"c_goldarah"=>$data['c_goldarah'],
							"n_hobby"=>$data['n_hobby'],
							"c_pendidikan"=>$data['c_pendidikan'],
							"c_identitas"=>$data['c_identitas'],
							"i_identitas"=>$data['i_identitas'],
							"n_alamat"=>$data['n_alamat'],
							"c_rt"=>$data['c_rt'],
							"c_rw"=>$data['c_rw'],
							"n_kelurahan"=>$data['n_kelurahan'],
							"n_kecamatan"=>$data['n_kecamatan'],
							"i_kodepos"=>$data['i_kodepos'],
							"c_propinsi"=>$data['c_propinsi'],
							"c_kota"=>$data['c_kota'],
							"i_tlprumah"=>$data['i_tlprumah'],
							"i_handphone"=>$data['i_handphone'],
							"n_email"=>$data['n_email'],
							"n_pekerjaan"=>$data['n_pekerjaan'],
							"i_entry"=>$data['i_entry'],
							"d_entry"=>date("Y-m-d"));

		$db->update('tm_keluarga',$ubah_data, "i_nik = '".trim($data['i_nik'])."' and c_keluarga = '".trim($data['c_keluarga'])."'");
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}
	public function hapusData($i_nik,$c_keluarga,$q_keluarga) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_keluarga = '".$c_keluarga."' and q_keluarga = '".$q_keluarga."' ";
		 $db->delete('tm_keluarga', $where);
		$db->commit();
	     return 'sukses';
	   } catch (Exception $e) {
         $db->rollBack();
         echo $e->getMessage().'<br>';
	     return 'gagal';
	   }
	}	
	
	public function hapusDataOrtu($i_nik,$c_keluarga) {
	   $registry = Zend_Registry::getInstance();
	   $db = $registry->get('db');
	   try {
	     $db->beginTransaction();
		 $where[] = "i_nik = '".$i_nik."' and c_keluarga = '".$c_keluarga."' ";
		 $db->delete('tm_keluarga', $where);
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
